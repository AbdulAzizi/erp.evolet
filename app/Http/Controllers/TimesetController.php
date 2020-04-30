<?php

namespace App\Http\Controllers;

use App\Timeset;
use App\User;
use Carbon\Carbon;

class TimesetController extends Controller
{
    public function index()
    {
        $from = new Carbon('2019-01-01');
        $from = $from->startOfDay();

        $to = new Carbon('2021-01-01');
        $to = $to->endOfDay();

        $timesets = Timeset::where('end_time', '>=', $from)
            ->where('end_time', '<=', $to)
            ->with('task.responsible')
            ->get();
            // ->groupBy('task.responsible_id')
            // ->toArray();

        // $timesets = json_encode($timesets);

        $users = User::all();

        return view('timesets.index', compact('timesets','users'));
    }
}
