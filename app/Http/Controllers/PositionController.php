<?php

namespace App\Http\Controllers;

use App\PositionLevel;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function loadPositionLevels()
    {
        $positionLevels = PositionLevel::all();

        return $positionLevels;
    }
}
