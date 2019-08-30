<?php

namespace App\Http\Controllers;

use App\JobDescription;
use App\User;
use Illuminate\Http\Request;

class ResponsibilityController extends Controller
{

    public function show(Request $request)
    {

        $user = User::where('id', $request->id)->with('responsibilities.descriptions')->first();

        return view('profile.responsibility', compact('user'));
    }
}
