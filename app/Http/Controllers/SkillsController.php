<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Skill;

class SkillsController extends Controller
{
    public function create(Request $request)
    {
        return  Skill::create([
            'type' => $request->type,
            'description' => $request->description,
            'resume_id' => $request->resume_id
        ]);
    }
    public function delete($id)
    {
        Skill::find($id)->delete();

        return 'success';
    }
}
