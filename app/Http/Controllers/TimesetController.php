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
        // Get from day
        $from = new Carbon();
        $from = $from->firstOfMonth();
        $from = $from->startOfDay();
        // Get to day
        $to = new Carbon();
        $to = $to->endOfMonth();
        $to = $to->endOfDay();

        $request = new Request([
            'from' => $from,
            'to' => $to,
        ]);

        $data = $this->getTimesets($request);
        $data['from'] = $from;
        $data['to'] = $to;

        return view('timesets.index', $data);
    }

    public function getTimesets(Request $request)
    {
        // Get exeptions
        $exeptions = Division::whereIn('abbreviation', ['ОРПО', 'ОУПС'])->get()->pluck('id');
        // Get auth user
        $auth = auth()->user();
        // Initialize timeset query
        $timesetQuery = Timeset::where('end_time', '>=', $request->from)
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
            $users = User::all();
        }

        $timesets = $timesetQuery->get();

        return compact('users', 'timesets');
    }
}
