<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Support\Facades\Auth;
use App\Division;
use App\Responsibility;
use App\Position;

class DivisionController extends Controller
{
    public function show()
    {
        $employeeDivisionId = Employee::whereUser(auth()->id())->without(['user', 'position', 'responsibility'])->first()->division_id;

        $division = Division::with('head','employees')->withDepth()->descendantsAndSelf($employeeDivisionId)->toTree();

        $responsibilities = Responsibility::where('name', '!=', 'Директор')->get();
        $positions = Position::where('name', '!=', 'Руководитель')->get();
        
        return view('division', compact('division', 'responsibilities', 'positions'));
    }
}
