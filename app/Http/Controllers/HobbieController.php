<?php

namespace App\Http\Controllers;

use App\Hobbie;
use Illuminate\Http\Request;

class HobbieController extends Controller
{
    public function create(Request $request)
    {
        return Hobbie::create([
            'type' => $request->type,
            'description' => $request->description,
            'resume_id' => $request->resume_id
        ]);
    }
    public function delete(Request $request)
    {
        Hobbie::find($request->id)->delete();

        return 'success';
    }
}
