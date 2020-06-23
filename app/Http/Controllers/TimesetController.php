<?php

namespace App\Http\Controllers;

use App\Division;
use App\Timeset;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TimesetController extends Controller
{
    public function index()
    {
        return view('timesets.index');
    }

    public function getTimesets(Request $request)
    {
        // Get exeptions
        $exeptions = Division::whereIn('abbreviation', ['ОРПО', 'ОУПС','Evolet'])->get()->pluck('id');
        // Get auth user
        $auth = auth()->user();
        // Initialize timeset query
        $timesetQuery = Timeset::where('start_time', '>=', $request->from)
            ->where('end_time', '<=', $request->to)
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
            $users = User::alone()->get();
        }

        $timesets = $timesetQuery->get();

        return compact('users', 'timesets');
    }
}
