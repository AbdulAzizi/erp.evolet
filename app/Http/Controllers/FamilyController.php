<?php

namespace App\Http\Controllers;

use App\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function create(Request $request)
    {
        return Family::create([
            'relation' => $request->relation,
            'birthday' => $request->birthday,
            'name' => $request->name,
            'resume_id' => $request->resume_id
        ]);
    }
    public function delete(Request $request)
    {
        Family::find($request->id)->delete();

        return 'success';
    }
}
