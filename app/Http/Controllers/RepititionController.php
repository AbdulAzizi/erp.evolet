<?php

namespace App\Http\Controllers;

use App\Repitition;
use App\Task;
use Illuminate\Http\Request;

class RepititionController extends Controller
{

    public function create(Request $request) {
        
        $task = Task::find($request->taskId);

        $repitition = Repitition::create([
            'range_period' => $request->period,
            'range' => $request->range,
            'end_date' => $request->end_date,
            'action' => $request->action,
            'weekDays' => json_encode($request->weekDays)
        ]);

        $repitition->tasks()->attach($task);
    }

    public function edit(Request $request, $id)
    {
        $repeat = Repitition::find($id);

        $task = Task::find($request->taskId);

        $repeat->update([
            'range' => $request->range,
            'range_period' => $request->period,
            'action' => $request->action,
            'end_date' => $request->end_date,
            'weekdays' => $request->weekdays
        ]);

        
        $task->start_date = $request->startTime;
        
        $task->save();
        
        $repeat->save();
        
        // return $task;
        return $repeat;
    }

    public function delete($id) {

        $task = Task::find($id);

        $repeatition = Repitition::find($task->repeat[0]->id);

        $repeatition->tasks()->detach($task);

        $repeatition->delete();

        $task->load('repeat');

        return $task;

    }
}
