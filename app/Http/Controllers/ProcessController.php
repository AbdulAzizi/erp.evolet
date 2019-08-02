<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Process;

class ProcessController extends Controller
{
    public function index(Request $request)
    {
        $processes = Process::all();

        return view('bp', compact('processes'));
    }
}
