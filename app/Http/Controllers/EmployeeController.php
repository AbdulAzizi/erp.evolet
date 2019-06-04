<?php

namespace App\Http\Controllers;

use App\Employee;
use App\User;
use Illuminate\Http\Request;
use Validator;

class EmployeeController extends Controller
{
    public function show()
    {
        $isUserEmployee = Employee::whereUser(auth()->id())->exists();

        if ($isUserEmployee) {
            return view('profile');
        }
        return redirect('home');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'positionId' => 'required',
            'responsibilityId' => 'required'
        ]);
        
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd()
        $newUser = User::create($request->only(['name', 'surname', 'email', 'password']));

        Employee::create([
            'user_id' => $newUser->id,
            'position_id' => $request->positionId,
            'responsibility_id' => $request->responsibilityId,
            'division_id' => $request->divisionId
        ]);
        
        return redirect()->back();
    }
}
