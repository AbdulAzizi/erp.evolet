<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Process;
use App\Tether;

class ProcessController extends Controller
{
    public function index(Request $request)
    {
        $processes = Process::with(['backTethers', 'frontTethers', 'processTasks', 'processTasks.position', 'processTasks.forms.fields'])->get();

        return view('bp', compact('processes'));
    }

    public function create(Request $request)
    {
        $process = Process::create([
            'name' => $request->name
        ]);

        $data = Process::find($process->id)->with(['frontTethers', 'backTethers'])->get();

        return $process->all();
    }

    public function update(Request $request)
    {
        $process = Process::find($request->id);

        $process->name = $request->name;

        $process->save();

        return $process->all();
    }

    public function delete(Request $request)
    {
        $process = Process::find($request->id);

        $front = $process->frontTethers;

        $back = $process->backTethers;

        if (count($front) > 0) {
            foreach ($front as $value) {
                $tether = Tether::find($value->id);
                $tether->delete();
            }
        }

        if (count($back) > 0) {
            foreach ($back as $value) {
                $tether = Tether::find($value->id);
                $tether->delete();
            }
        }

        $process->delete();

        return redirect()->back();
    }
}
