<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function create(Request $request)
    {
        return Job::create([
            'company_name' => $request->company_name,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'positionLevel' => $request->positionLevel,
            'location' => $request->location,
            'resume_id' => $request->resume_id
        ]);
    }

    public function delete(Request $request)
    {
        Job::find($request->id)->delete();

        return "success";
    }
}
