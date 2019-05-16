<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $auth_user = \Auth::user();
        $allTasks = $auth_user->employee->allTasks(); // FIXME  unemployed users doesnt have employee relationship
        // return $allTasks;
        return view('tasks.index',compact('allTasks'));
    }
}
