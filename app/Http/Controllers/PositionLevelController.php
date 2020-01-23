<?php

namespace App\Http\Controllers;

use App\PositionLevel;

class PositionLevelController extends Controller
{
    public function loadPositionLevels()
    {
        $positionLevels = PositionLevel::all();

        return $positionLevels;
    }
}
