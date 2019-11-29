<?php

namespace App\Http\Controllers;

use App\Process;
use App\Task;
use App\Tether;
use Illuminate\Http\Request;

class TethersController extends Controller
{
    public function create(Request $request)
    {
        $tether = Tether::create([
            'from_process_id' => $request->from_process_id,
            'to_process_id' => $request->to_process_id,
            'action_text' => $request->action_text
        ]);

        $process = Process::all();

        return $process;
    }

    public function delete(Request $request)
    {
        $tether = Tether::find($request->id);

        $tether->delete();

        return redirect()->back();
    }
}
