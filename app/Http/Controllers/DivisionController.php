<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Support\Facades\Auth;
use App\Division;

class DivisionController extends Controller
{
    public function show()
    {
        $division = Division::with('head','employees', 'descendants.employees')->find(Employee::byUser(auth()->id())->division_id);
        
        return view('division', compact('division'));
    }
}
