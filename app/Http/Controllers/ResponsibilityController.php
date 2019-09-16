<?php

namespace App\Http\Controllers;

use App\Division;
use App\JobDescription;
use App\User;
use App\Responsibility;
use Illuminate\Http\Request;

class ResponsibilityController extends Controller
{

    public function show(Request $request)
    {
        $user = User::with('responsibilities.descriptions')->find($request->id);
        
        return view('profile.responsibility', compact('user'));
    }

    public function store()
    {
        JobDescription::create([
            'responsibility_id' => request('responsibility_id'),
            'text' => request('text')
        ]);

        return redirect()->back();
    }
}
