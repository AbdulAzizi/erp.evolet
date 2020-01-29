<?php

namespace App\Http\Controllers;

use App\Position;
use App\PositionLevel;
use Illuminate\Http\Request;

class PositionLevelController extends Controller
{
    public function loadPositionLevels()
    {
        $positionLevels = PositionLevel::all();

        return $positionLevels;
    }

    public function loadDivisionPositions(Request $request)
    {
        $divisionPositions = Position::where('division_id', $request->id)->get();

        return $divisionPositions;
    }
}
