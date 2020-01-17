<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function loadPositions()
    {
        $positions = Position::all();

        return $positions;
    }
}
