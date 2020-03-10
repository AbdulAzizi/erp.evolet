<?php

namespace App\Http\Controllers;

use App\Division;
use App\Events\TaskCreatedEvent;
use App\Events\TaskForwardedEvent;
use App\Filters\TaskFilters;
use App\Option;
use App\Question;
use App\Status;
use App\Task;
use App\Timeset;
use App\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request, TaskFilters $filters)
    {
        $authUser = \Auth::user();
        $statuses = Status::all();

        $tasks = Task::filter($filters)->with(
            'from',
            'responsible',
            'watchers',
            'status',
            'tags',
            'responsibilityDescription'
        )->get();

        // foreach ($tasks as $task) {
        //     // If task is from process
        //     if ($task->from_type == "App\Process") {
        //         // Load Tethers and forms for each tether
        //         $task->from->load('frontTethers.form.fields.type', 'backTethers');
        //         // return $task;
        //     }
        // }
        // All Users needed while choosing watchers
        $users = User::with(['division'])->get();
        $divisions = Division::with('users')->whereHas('users')->get();

        // $notifications = $authUser->notifications;

        return view('tasks.index', compact(
            'tasks',
            'users',
            'statuses',
            'authUser',
            'divisions'
        ));
    }

    public function store(Request $request)
    {
        // validate obligatory fields
        $validatedData = $request->validate([
            'responsibility_description' => 'required',
            'assignees' => 'required',
            'deadline' => 'required',
            'estimatedTaskTime' => 'required',
        ]);
        // Decode things that must be decoded
        $assignees = json_decode($request->assignees);

        // Get new Status instance
        $newStatus = \App\Status::where('name', 'Новый')->first();
        // Empty array to keep query
        $tasks = [];
        // Loop through assignees
        foreach ($assignees as $key => $assigneeID) {
            // Push each query array to one that must be executed for Tasks
            $tasks[] = Task::create([
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
                'responsibility_description_id' => $request->responsibility_description,
            ]);
        }
        // Decode watchers
        $watchers = json_decode($request->watchers);
        if (count($watchers)) {
            // Get all Watcher Users
            $watchers = User::alone()->find($watchers);
        }
        $tags = json_decode($request->existingTags);
        $poll = json_decode($request->poll);
        // if there is a poll
        if ($poll) {
            $this->createPoll($poll, $tasks);
        }
        // Loop through Tasks
        foreach ($tasks as $task) {

            // TODO On the clide side restrickt User to add himself as a watcher
            // TODO select auth user as task responsible by deafauld on the client side

            if ($request->watchers) {
                // Attach Watchers to Task
                $task->watchers()->attach($watchers);
            }
            if (count($tags)) {
                $task->tags()->attach($tags);
            }

            //Log creation to tasks History
            event(new TaskCreatedEvent($task));
        }
        // Redirect to Tasks Index page
        return redirect()->route('tasks.show', $task->id);
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
            'questionTasks.answers',
            'questionTasks.question.options',
            // 'messages',
            'forms.fields',
            'timeSets',
            'responsibilityDescription'
        )->find($id);
        // return $task;

        if ($task->from_type == "App\Process") {
            $task->load('products.messages');
        } else {
            $task->load('messages');
        }

        // if has front tether load it
        if ($task->from_type == "App\Process") {
            $task->from->load('frontTethers.forms.fields');
        }

        $task->readed = 1;
        $task->save();

        return view('tasks.show', compact('task'));
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
        $taskIDs = [];
        foreach ($tasks as $task) {
            $taskIDs[] = $task->id;
        }
        $question->task()->attach($taskIDs);
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
            'status',
        ]);

        return $task;
    }

    public function addStatus(Request $request)
    {
        $status = Status::create([
            'name' => $request->name,
            'user_id' => $request->auth_user_id,
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
            'timeSets',
            'messages.sender',
            'questionTasks.answers',
            'questionTasks.question.options',
            'forms.fields'
        )->find($id);

        // if has front tether load it
        if ($task->from_type == "App\Process") {
            $task->load('products.messages');
        } else {
            $task->load('messages');
        }

        // if has front tether load it
        if ($task->from_type == "App\Process") {
            $task->from->load('frontTethers.forms.fields');
        }

        return $task;
    }

    public function start($id)
    {
        $timeset = Timeset::create([
            'task_id' => $id,
            'start_time' => date(now()),
        ]);

        $InProcess = Status::where('name','В процессе')->first();
        
        $task = Task::with('status', 'timeSets')->find($id);
        $task->status()->associate( $InProcess );
        $task->save();
        
        return $task;
    }

    public function pause($id)
    {
        $paused = Status::where('name','Приостановлен')->first();

        $task = Task::with('timeSets')->find($id);
        $task->status()->associate( $paused );
        $task->save();

        $lastTimeSet = $task->timeSets->last();
        $lastTimeSet->end_time = date(now());
        $lastTimeSet->save();

        $task->load('status', 'timeSets');

        return $task;
    }

    public function stop($id)
    {
        $stop = Status::where('name','Закрытый')->first();

        $task = Task::with('timeSets')->find($id);
        $task->status()->associate( $stop );
        $task->save();

        $lastTimeSet = $task->timeSets->last();

        if($lastTimeSet->end_time == null){
            $lastTimeSet->end_time = date(now());
            $lastTimeSet->save();
        }

        return $task;
    }

    public function mark(Request $request)
    {
        $task = Task::find($request->id);

        $task->readed = $request->readed;
        $task->save();

        return $task;
    }
}
