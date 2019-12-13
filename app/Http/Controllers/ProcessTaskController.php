<?php

namespace App\Http\Controllers;

use App\ProcessTask;
use Illuminate\Http\Request;
use stdClass;

class ProcessTaskController extends Controller
{
    public function create(Request $request)
    {
       $title = $request->data['processTaskName'];
       $responsible = $request->data['processTaskResponsible'];
       $description = $request->data['processTaskDescription'];

       $processTask = ProcessTask::create([
           'process_id' => $request->processId,
           'title' => $title,
           'responsibility_id' => $responsible,
           'description' => $description,
           'planned_time' => $request->planned_time
       ]);

       $processTask->save();

       $processTaskWithPivot = ProcessTask::with('responsibility')->find($processTask->id);

       return $processTaskWithPivot;
    }

    public function delete (Request $request)
    {
        $processTask = ProcessTask::find($request->id);

        $processTask->delete();
    }

    public function edit(Request $request)
    {
       $title = $request->data['processTaskName'];
       $responsibility_id = $request->data['processTaskResponsible'];
       $description = $request->data['processTaskDescription'];

        $processTask = ProcessTask::find($request->id);

        $processTask->update([
            'title' => $title,
            'responsibility_id' => $responsibility_id ,
            'description' => $description,
            'planned_time' => $request->planned_time
        ]);

        $processTask->save();

        $processTaskWithPivot = ProcessTask::with('responsibility', 'forms')->find($processTask->id);

        return $processTaskWithPivot;
    }
}
