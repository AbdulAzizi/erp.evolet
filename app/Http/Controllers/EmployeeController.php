<?php

namespace App\Http\Controllers;

use App\Employee;

class EmployeeController extends Controller
{
    public function show()
    {
        $employee = Employee::where('user_id',auth()->id())->first();
        
        if($employee){
            return view('profile');
        }
        return redirect('home');
    }
}
