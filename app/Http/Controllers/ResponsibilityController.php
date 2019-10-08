<?php

namespace App\Http\Controllers;

use App\Division;
use App\JobDescription;
use App\User;
use App\Responsibility;
use Illuminate\Http\Request;

class ResponsibilityController extends Controller
{

    public function show(Request $request, $id)
    {
        $user = User::with('responsibilities.descriptions')->find($request->id);
        $division = new Division();

        if (\Auth::user()->position->name = "Руководитель" && \Auth::user()->division->id == $user->division->id)
            $division = Division::with('responsibilities')->find($user->division_id);
        return view('profile.responsibility', compact('user', 'division'));
    }

    public function store()
    {
        $responsibility = Responsibility::create([
            'name' => request('name'),
            'division_id' => \Auth::user()->division_id
        ]);

        JobDescription::create([
            'responsibility_id' => $responsibility->id,
            'text' => request('text'),
        ]);

        return redirect()->back();
    }
    public function createJobDescription()
    {
        JobDescription::create([
            'responsibility_id' => request('responsibility_id'),
            'text' => request('text')
        ]);

        return  redirect()->back();
    }
}
