<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
    public function index()
    {
        $coleguesFromDivision = Employee::byUserId(auth()->id())->division->load('employees.user');
        dd($coleguesFromDivision->toArray());
        return view('division');
    }
}
