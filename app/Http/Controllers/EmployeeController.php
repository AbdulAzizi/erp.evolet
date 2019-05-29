<?php

namespace App\Http\Controllers;

use App\Employee;

class EmployeeController extends Controller
{
    public function show()
    {
        $isUserEmployee = Employee::whereUser(auth()->id())->exists();
        
        if($isUserEmployee){
            return view('profile');
        }
        return redirect('home');
    }
}
