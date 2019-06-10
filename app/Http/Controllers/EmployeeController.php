<?php

namespace App\Http\Controllers;

use App\Employee;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Support\Facades\Password;

class EmployeeController extends Controller
{
    public function show(Request $request)
    {
        $employee = Employee::find($request->id);

        $isUserEmployee = Employee::whereUser(auth()->id())->exists();

        if ($isUserEmployee) {
            return view('profile', compact('employee'));
        }
        return redirect('home');
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
        ]);

        Employee::create([
            'user_id' => $newUser->id,
            'position_id' => $request->positionId,
            'responsibility_id' => $request->responsibilityId,
            'division_id' => $request->divisionId,
        ]);

        Password::broker()->sendResetLink(['email' => $newUser->email]);

        return redirect()->back();
    }
}
