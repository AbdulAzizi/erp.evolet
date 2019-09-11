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
        $user = User::where('id', $request->id)->with('responsibilities.descriptions')->first();
        $responsibilities = Responsibility::all();

        return view('profile.responsibility', compact('user', 'responsibilities'));
    }

    public function store()
    {
        JobDescription::create([
            'responsibility_id' => request('responsibility_id'),
            'text' => request('text')
        ]);

        return redirect('/users/7/responsibility');
    }
}
