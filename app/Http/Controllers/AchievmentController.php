<?php

namespace App\Http\Controllers;

use App\Achievment;
use Illuminate\Http\Request;

class AchievmentController extends Controller
{
    public function create(Request $request)
    {
        return Achievment::create([
            'type' => $request->type,
            'description' => $request->description,
            'resume_id' => $request->resume_id
        ]);
    }
    public function delete(Request $request)
    {
        Achievment::find($request->id)->delete();

        return 'success';
    }
}
