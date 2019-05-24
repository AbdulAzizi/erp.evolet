<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
    public function show()
    {
        $division = Employee::byUserId(auth()->id())->division->load(['head','employees']);
        // dd($division->toArray());
        return view('division', compact('division'));
    }
}
