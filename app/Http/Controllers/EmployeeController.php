<?php

namespace App\Http\Controllers;

use App\Employee;

class EmployeeController extends Controller
{
    public function show()
    {
        $employee = Employee::where('user_id', auth()->id())
            ->with('user')
            ->with('division:id,name,abbreviation')
            ->with('position')
            ->with('responsibility')
            ->first();

        // dd($employee);
        return view('profile', compact('employee'));
    }
}
