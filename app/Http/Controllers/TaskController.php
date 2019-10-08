<?php

namespace App\Http\Controllers;

use App\Events\TaskCreatedEvent;
use App\Events\TaskForwardedEvent;
use App\History;
use App\Notifications\AssignedAsWatcher;
use App\Notifications\AssignedToTask;
use App\Option;
use App\Question;
use App\Tag;
use App\Task;
use App\User;
use App\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    public function index()
    {
        $authUser = \Auth::user();

        $statuses = Status::all();

        $tasks = Task::where('responsible_id', $authUser->id)
            ->with(
                'from',
                'responsible',
                'watchers',
                'status',
                'tags'
                //     'history.user',
                //     'polls.options.users'
            )
            ->get();

        // foreach ($tasks as $task) {
        //     // If task is from process
        //     if ($task->from_type == "App\Process") {
        //         // Load Tethers and forms for each tether
        //         $task->from->load('frontTethers.form.fields.type', 'backTethers');
        //         // return $task;
        //     }
        // }

        $tags = Tag::all();
        // All Users needed while choosing user assignee
        $users = User::with(['division'])->get();
        // $notifications = $authUser->notifications;

        return view('tasks.index', compact(
            'tasks',
            'users',
            'tags',
            'statuses',
            'authUser'
        ));
    }

    public function store(Request $request)
    {
        // validate obligatory fields
        $validatedData = $request->validate([
            'title' => 'required',
            'assignees' => 'required',
            'deadline' => 'required',
            'estimatedTaskTime' => 'required',
        ]);
        // Decode things that must be decoded
        $assignees = json_decode($request->assignees);
        $watchers = json_decode($request->watchers);
        $newTags = json_decode($request->newTags);
        $existingTags = json_decode($request->existingTags);
        $poll = json_decode($request->poll);

        // Get new Status instance
        $newStatus = \App\Status::where('name', 'Новый')->first();
        // Empty array to keep query
        $data = [];
        // Loop through assignees
        foreach ($assignees as $key => $assigneeID) {
            // Push each query array to one that must be executed for Tasks
            $data[] = [
                'title' => $request->title,
                'description' => $request->description,
                'status_id' => $newStatus->id,
                'priority' => $request->priority === null ? 1 : $request->priority,
                'planned_time' => $request->estimatedTaskTime,
                'start_date' => $request->start_date,
                'deadline' => $request->deadline,
                'responsible_id' => $assigneeID,
                'from_id' => auth()->id(),
                'from_type' => User::class,
                'created_at' => Carbon::now(),
            ];
        }
        // Insert all Tasks at once
        Task::insert($data);
        // Loop through newTags
        foreach ($newTags as $newTag) {
            // Create new tags and merge them to existing tags array
            $existingTags[] = Tag::create(['name' => $newTag])->id;
        }
        // Get all Watcher Users
        $watchers = User::alone()->find($watchers);
        // Get all tasks that have been created
        $tasks = Task::where('title', $request->title)->where('created_at', $data[0]['created_at'])->get();
        // if there is a poll
        if ($poll) {
            $this->createPoll($poll, $tasks);
        }
        // Loop through Tasks
        foreach ($tasks as $task) {

            // TODO On the clide side restrickt User to add himself as a watcher
            // TODO select auth user as task responsible by deafauld on the client side

            // Attach Task Author as a Watcher
            $task->watchers()->attach(auth()->user());
            // Attach Watchers to Task
            $task->watchers()->attach($watchers);
            // Notify Watchers
            Notification::send($watchers, new AssignedAsWatcher($task->from, $task->responsible, $task));
            // Attach Tags to Task
            $task->tags()->attach($existingTags);
            // Notify Assignees
            $task->responsible->notify(new AssignedToTask($task->from, $task));
            //Log creation to tasks History
            event(new TaskCreatedEvent($task));
        }
        // Redirect to Tasks Index page
        return redirect()->route('tasks.index');
    }

    public function show($id)
    {
        $task = Task::with(
            'watchers',
            'responsible',
            'from',
            'status',
            'tags',
            'history.user',
            // 'polls.answers',
            'polls.options.users',
            'messages.sender'
        )->find($id);
        // return $task;
        // if has front tether load it
        if ($task->from_type == "App\Process") {
            $task->from->load('frontTethers.form.fields', 'backTethers');
        }

        $users = User::with(['division'])->get();

        // return $task;
        return view('tasks.show', compact('task', 'users'));
    }

    public function update($id, Request $request)
    {
        $task = Task::find($id);

        $taskFields = collect($task->getAttributes());

        $updatedColumns = collect($request->all())->intersectByKeys($taskFields);

        foreach ($updatedColumns as $columnName => $updatedValue) {
            switch ($columnName) {
                case 'responsible_id':
                    $this->forwardTask($task, $updatedValue);
                    break;
            }
        }

        return redirect()->route('tasks.index');
    }

    /**
     * Create a poll
     *
     * @param [object] $poll = { 'question'=>'', 'options'=>['a','b','c',..] }
     * @param [object] $tasks
     * @return void
     */
    private function createPoll($poll, $tasks)
    {
        // make question
        $question = Question::create(['body' => $poll->question]);
        //holder for questions
        $options = [];
        // collect questions
        foreach ($poll->options as $option) {
            // if option is not empty
            if ($option != '')
            // add option to array
            {
                $options[] = new Option(['body' => $option]);
            }
        }
        // attach options to question
        $question->options()->saveMany($options);
        // attach poll to task
        $question->task()->attach($tasks);
        // attach options for return
        $question->options = $options;
        // return
        return $question;
    }

    /**
     * Helpers
     *
     */

    private function forwardTask(Task $task, $newResponsibleID)
    {
        $oldTask = $task->replicate();

        $author = auth()->user();
        $authorDivision = $author->load('division')->division;

        $isAuthorHead = $author->id === $authorDivision->head_id;

        if (!$isAuthorHead) {
            return;
        }

        $task->responsible_id = $newResponsibleID;

        $task->save();

        event(new TaskForwardedEvent($oldTask, $task));
    }

    public function changeStatus(Request $request)
    {
        $task = Task::find($request->id);

        $task->status_id = $request->statusId;

        $task->save();

        $task->load([
            'from',
            'responsible',
            'watchers',
            'status'
        ]);

        return $task;
    }

    public function addStatus(Request $request)
    {
        $status = Status::create([
            'name' => $request->name,
            'user_id' => $request->auth_user_id
        ]);

        return $status;
    }

    public function changeStatusName(Request $request)
    {
        $status = Status::find($request->id);

        $status->name = $request->name;

        $status->save();

        return 'success';
    }

    public function deleteStatus(Request $request)
    {
        $status = Status::find($request->id);

        $status->delete();

        return 'success';
    }

    public function selectTask($id)
    {
        $task = Task::with(
            'watchers',
            'responsible',
            'from',
            'status',
            'tags',
            'history.user',
            'polls.options.users',
            'messages.sender'
        )->find($id);

        // if has front tether load it
        if ($task->from_type == "App\Process") {
            $task->from->load('frontTethers.form.fields', 'backTethers');
        }

        return $task;
    }
}
