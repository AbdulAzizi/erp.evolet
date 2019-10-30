<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Process;

class ProcessController extends Controller
{
    public function index(Request $request)
    {
        $processes = Process::with(['backTethers', 'frontTethers'])->get();

        return view('bp', compact('processes'));
    }

    public function create(Request $request)
    {
        $process = Process::create([
            'name' => $request->name
        ]);

        $data = Process::find($process->id)->with(['frontTethers', 'backTethers'])->get();

        return $data->last();
    }
}
