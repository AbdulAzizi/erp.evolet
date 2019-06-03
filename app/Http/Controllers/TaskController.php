<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
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
        $tasks = Task::where('responsible_id',$authUser->employee->id)
                            ->with('from')
                            ->get();
        // return $tasks;

        $employees = Employee::with(['division','responsibility'])->get();
        
        // return $employees;
        return view('tasks.index',compact('tasks', 'employees'));
    }

    public function store(Request $request)
    {
        $assignees = json_decode($request->assignees);
        $watchers = json_decode($request->watchers);
        // $estimatedTaskTime = json_decode($request->estimatedTaskTime);
        
        // $planned_time = Carbon::now()->timestamp(000000000);
        // return  $estimatedTaskTime;
        // if($estimatedTaskTime->days)
        //     $planned_time->addDays($estimatedTaskTime->days);
        // if($estimatedTaskTime->hours)
        //     $planned_time->addHours($estimatedTaskTime->hours);
        // if($estimatedTaskTime->minutes)
        //     $planned_time->addMinutes($estimatedTaskTime->minutes);
                
        //   return      $planned_time->diffInMinutes(); 

        $data = [];

        foreach ($assignees as $key => $assigneeID) {
            $data[] = [
                'title' => $request->title,
                'description' => $request->description,
                'status' => 0,
                'priority' => $request->priority,
                'planned_time' => $request->estimatedTaskTime,
                'deadline' => $request->deadline,
                'responsible_id' => $assigneeID,
                'from_id' => Employee::byUser(auth()->id())->id,
                'from_type' => Employee::class
            ];
        }

        Task::insert($data);

        return redirect()->route('tasks.index');

    }
}
