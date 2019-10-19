<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function create(Request $request)
    {
        return Language::create([
            'name' => $request->name,
            'level' => $request->level,
            'resume_id' => $request->resume_id
        ]);
    }
    public function delete(Request $request)
    {
        Language::find($request->id)->delete();

        return 'success';
    }
}
