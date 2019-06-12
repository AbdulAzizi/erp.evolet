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

        $tasks = Task::where('responsible_id',$authUser->id)
                            ->with('from')
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

        // return $request;
        
        $assignees = json_decode($request->assignees);
        $watchers = json_decode($request->watchers);

        $data = [];

        foreach ($assignees as $key => $assigneeID) {
            $data[] = [
                'title' => $request->title,
                'description' => $request->description,
                'status' => 0,
                'priority' => $request->priority ? $request->priority : 1,
                'planned_time' => $request->estimatedTaskTime,
                'deadline' => $request->deadline,
                'responsible_id' => $assigneeID,
                'from_id' => auth()->id(),
                'from_type' => User::class
            ];
        }

        Task::insert($data);

        return redirect()->route('tasks.index');

    }
}
