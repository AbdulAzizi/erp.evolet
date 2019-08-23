<?php

namespace App\Http\Controllers;

use App\User;
use App\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{


    public function index(Request $request)
    {

        $users = User::all();

        return view('profile.index', compact('users'));
    }

    public function show(Request $request)
    {

        $user = User::find($request->id);

        return view('profile.tasks', compact('user'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users,email',
            'positionId' => 'required',
            'responsibilityId' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $randomPassword = Str::random(10);

        $newUser = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => $randomPassword,
            'position_id' => $request->positionId,
            'responsibility_id' => $request->responsibilityId,
            'division_id' => $request->divisionId,
        ]);

        Password::broker()->sendResetLink(['email' => $newUser->email]);

        return redirect()->back();
    }
}
