<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $authUser = \Auth::user();
        // $allTasks = $authUser->employee->allTasks(); // FIXME  unemployed users doesnt have employee relationship
        $tasks = \App\Task::where('from_id',$authUser->employee->id)
                            ->with('from')
                            ->get();
        // return $tasks;
        $employees = \App\Employee::with(['division','responsibility'])->get();
        // return $employees;
        return view('tasks.index',compact('tasks', 'employees'));
    }
}
