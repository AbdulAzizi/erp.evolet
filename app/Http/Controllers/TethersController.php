<?php

namespace App\Http\Controllers;

use App\Task;
use App\Tether;
use Illuminate\Http\Request;

class TethersController extends Controller
{
    public function create(Request $request)
    {
        return Tether::create([
            'from_process_id' => $request->from_process_id,
            'to_process_id' => $request->to_process_id,
            'action_text' => null
        ]);
    }
}
