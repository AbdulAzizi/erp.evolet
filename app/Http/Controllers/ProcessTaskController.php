<?php

namespace App\Http\Controllers;

use App\Form;
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
       $planned_time = ($request->data['processTaskDays'] * 86400000) + ($request->data['processTaskHours'] * 3600000) + ($request->data['processTaskMinutes'] * 60000);

       $processTask = ProcessTask::create([
           'process_id' => $request->processId,
           'title' => $title,
           'position_id' => $responsible,
           'description' => $description,
           'planned_time' => $planned_time
       ]);

       $processTask->save();

       $processTaskWithPivot = ProcessTask::with('position', 'forms')->find($processTask->id);

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
       $position_id = $request->data['processTaskResponsible'];
       $description = $request->data['processTaskDescription'];

        $processTask = ProcessTask::find($request->id);

        $processTask->update([
            'title' => $title,
            'position_id' => $position_id ,
            'description' => $description,
            'planned_time' => $request->planned_time
        ]);

        $processTask->save();

        $processTaskWithPivot = ProcessTask::with('position', 'forms')->where('process_id', $request->processId)->get();

        return $processTaskWithPivot;
    }

    public function addForm(Request $request)
    {
        $form = Form::find($request->formId);

        $processTask = ProcessTask::find($request->processTaskId);

        $processTask->forms()->attach($form);

        $formWithFields = Form::with('fields')->find($form->id);

        return $formWithFields;
    }
}
