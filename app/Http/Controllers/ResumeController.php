<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ResumeController extends Controller
{

    public function show(Request $request)
    {
        $user = User::find($request->id);

        $resume = $user->resume()->get();

        return view('profile.curriculum', compact('user', 'resume'));
    }

    public function store(Request $request)
    {
        // return $request->degrees;
        $degrees = json_decode($request->degrees);

        return $degrees[0]->degree;
    }
}
