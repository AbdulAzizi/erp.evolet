<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', auth()->id())->first();
        dd($employee);
        return view('division');
    }
}
