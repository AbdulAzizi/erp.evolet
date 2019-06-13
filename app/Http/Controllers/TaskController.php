<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Task;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Tag;

class TaskController extends Controller
{
    public function index()
    {
        $authUser = \Auth::user();
        // $allTasks = $authUser->employee->allTasks(); // FIXME  unemployed users doesnt have employee relationship
        $tasks = Task::where('responsible_id',$authUser->employee->id)
                            ->with('from','responsible','watchers','status','tags')
                            ->get();
        // return $tasks;

        $employees = Employee::with(['division','responsibility'])->get();
        $tags = Tag::all();
        
        // return $employees;
        return view('tasks.index',compact('tasks','employees','tags'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required',
            'assignees' => 'required',
            'deadline' => 'required',
            'estimatedTaskTime' => 'required',
        ]);

        $assignees = json_decode($request->assignees);
        $watchers = json_decode($request->watchers);
        $newTags = json_decode($request->newTags);
        $existingTags = json_decode($request->existingTags);

        // return $request;

        $data = [];

        $newStatus = \App\Status::where('name','Новый')->first();

        foreach ($assignees as $key => $assigneeID) {
            $data[] = [
                'title' => $request->title,
                'description' => $request->description,
                'status_id' => $newStatus->id,
                'priority' => $request->priority === null ? 1 : $request->priority,
                'planned_time' => $request->estimatedTaskTime,
                'deadline' => $request->deadline,
                'responsible_id' => $assigneeID,
                'from_id' => Employee::byUser(auth()->id())->id,
                'from_type' => Employee::class,
                'created_at' => Carbon::now()
            ];
        }

        Task::insert($data);
        
        $tasks = Task::where('title', $request->title)->get();

        foreach ($newTags as $newTag) {
            $existingTags[] =  Tag::create(['name' => $newTag])->id;
        }

        foreach ($tasks as $task) {
            $task->watchers()->attach($watchers);
            $task->tags()->attach($existingTags);
        }

        return redirect()->route('tasks.index');

    }
}
