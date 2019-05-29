<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Support\Facades\Auth;
use App\Division;

class DivisionController extends Controller
{
    public function show()
    {
        $employeeDivisionId = Employee::byUser(auth()->id())->division_id;

        $division = Division::with('head','employees')->descendantsAndSelf($employeeDivisionId)->toTree();
        
        return view('division', compact('division'));
    }
}
