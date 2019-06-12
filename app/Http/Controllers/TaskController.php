<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        $authUser = \Auth::user();
        // $allTasks = $authUser->employee->allTasks(); // FIXME  unemployed users doesnt have employee relationship
        $tasks = Task::where('responsible_id',$authUser->id)
                            ->with('from','responsible','watchers','status')
                            ->get();

        $users = User::with(['division'])->get();
        
        return view('tasks.index',compact('tasks', 'users'));
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

        $data = [];

        $newStatus = \App\Status::where('name','Новый')->first();

        foreach ($assignees as $key => $assigneeID) {
            $data[] = [
                'title' => $request->title,
                'description' => $request->description,
                'status_id' => $newStatus->id,
                'priority' => $request->priority ? $request->priority : 1,
                'planned_time' => $request->estimatedTaskTime,
                'deadline' => $request->deadline,
                'responsible_id' => $assigneeID,
                'from_id' => auth()->id(),
                'from_type' => User::class,
                'created_at' => Carbon::now()
            ];
        }

        Task::insert($data);
        
        $tasks = Task::where('title',$request->title)->get();
        
        foreach ($tasks as $task) {
            $task->watchers()->attach($watchers);
        }
        

        return redirect()->route('tasks.index');

    }
}
