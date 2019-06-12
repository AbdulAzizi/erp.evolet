<?php

namespace App\Http\Controllers;

use App\Division;
use App\Responsibility;
use App\Position;

class DivisionController extends Controller
{
    public function show()
    {
        $userDivisionId = auth()->user()->division_id;

        $division = Division::with('head','users')->withDepth()->descendantsAndSelf($userDivisionId)->toTree();

        $responsibilities = Responsibility::where('name', '!=', 'Директор')->get();
        $positions = Position::where('name', '!=', 'Руководитель')->get();
        
        return view('division', compact('division', 'responsibilities', 'positions'));
    }
}
