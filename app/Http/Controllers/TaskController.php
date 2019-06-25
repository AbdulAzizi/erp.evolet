<?php

namespace App\Http\Controllers;

use App\Notifications\AssignedAsWatcher;
use App\Notifications\AssignedToTask;
use App\Tag;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    public function index()
    {
        $authUser = \Auth::user();

        $tasks = Task::where('responsible_id', $authUser->id)
            ->with('from', 'responsible', 'watchers', 'status', 'tags')
            ->get();

        $tags = Tag::all();
        $users = User::with(['division'])->get();
        // $notifications = $authUser->notifications;

        return view('tasks.index', compact('tasks', 'users', 'tags'));
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
        // Get all tasks that have been inserted
        $tasks = Task::where('title', $request->title)->get();
        // Loop through Tasks
        foreach ($tasks as $task) { 
            
            // TODO On the clide side restrickt User to add himself as a watcher 
            // TODO select auth user as task responsible by deafauld on the client side

            // Attach Task Author as a Watcher
            $task->watchers()->attach( auth()->user() );
            // Attach Watchers to Task
            $task->watchers()->attach( $watchers );
            // Notify Watchers
            Notification::send($watchers, new AssignedAsWatcher($task->from, $task->responsible, $task));
            // Attach Tags to Task
            $task->tags()->attach($existingTags);
            // Notify Assignees
            $task->responsible->notify(new AssignedToTask($task->from, $task));
        }
        // Redirect to Tasks Index page
        return redirect()->route('tasks.index');
    }
}
