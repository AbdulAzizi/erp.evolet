<?php

namespace App\Http\Controllers;

use App\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function create(Request $request)
    {
        return Education::create([
            'degree' => request('degree'),
            'name' => request('name'),
            'start_at' => request('start_at'),
            'end_at' => request('end_at'),
            'specialty' => request('specialty'),
            'resume_id' => request('resume_id')
        ]);
    }
    public function delete(Request $request)
    {
        Education::find($request->id)->delete();

        return "success";
    }
}
