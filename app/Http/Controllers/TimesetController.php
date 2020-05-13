<?php

namespace App\Http\Controllers;

use App\Division;
use App\Timeset;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TimesetController extends Controller
{
    public function index()
    {
        // Get exeptions
        $exeptions = Division::whereIn('abbreviation', ['ОРПО', 'ОУПС'])->get()->pluck('id');
        // Get auth user
        $auth = auth()->user();
        // Get from day
        $from = new Carbon('2019-01-01');
        $from = $from->startOfDay();
        // Get to day
        $to = new Carbon('2021-01-01');
        $to = $to->endOfDay();

        // Initialize timeset query
        $timesetQuery = Timeset::where('end_time', '>=', $from)
            ->where('end_time', '<=', $to)
            ->with('task.responsible');

        // if user is not an exeption
        if (!$exeptions->contains($auth->division_id)) {
            $userDivisionsIDs = Division::descendantsAndSelf($auth->division_id)->pluck('id');
            $users = User::whereIn('division_id', $userDivisionsIDs)->get();
            $userIDs = $users->pluck('id');

            $timesetQuery->whereHas('task', function (Builder $task) use ($userIDs) {
                $task->whereIn('responsible_id', $userIDs);
            });
        } else {
            $users = User::all();
        }

        $timesets = $timesetQuery->get();
        
        return view('timesets.index', compact('timesets', 'users'));
    }
}
