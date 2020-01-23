<?php

namespace App\Http\Controllers;

use App\JobDescription;
use Illuminate\Http\Request;

class JobDescriptionController extends Controller
{
    public function delete(Request $request)
    {
        $jobDescription = JobDescription::find($request->id);

        $jobDescription->delete();
    }

    public function edit(Request $request)
    {
        $jobDescription = JobDescription::find($request->id);
        $jobDescription->level = $request->description['level'];
        $jobDescription->text = $request->description['text'];
        $jobDescription->planned_time = $request->days * 86400000 + $request->hours * 3600000 + $request->minutes * 60000;

        $jobDescription->save();

        return $jobDescription;
    }
}
