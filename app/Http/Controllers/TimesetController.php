<?php

namespace App\Http\Controllers;

use App\Division;
use App\Timeset;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TimesetController extends Controller
{
    public function index()
    {
        $divisions = collect([]);

        if ($this->isExeption()) {
            $divisions = Division::whereHas('users')->get();
        }

        return view('timesets.index', compact('divisions'));
    }

    public function getTimesets(Request $request)
    {
        // Initialize timeset query
        $timesetQuery = Timeset::whereDate('start_time', '>=', $request->from)
                                ->whereDate('end_time', '<=', $request->to)
                                ->with('task');

        // if user is not an exeption
        if (!$this->isExeption()) {
            // get divisions of auth
            $divisionsIDs = Division::descendantsAndSelf(auth()->user()->division_id)->pluck('id');
            // get users of those divisions
            $users = User::whereIn('division_id', $divisionsIDs)->get();
            // get user ids
            $userIDs = $users->pluck('id');
            // timesets that belongs to those users
            $timesetQuery->whereHas('task', function (Builder $task) use ($userIDs) {
                $task->whereIn('responsible_id', $userIDs);
            });
        } else {
            // if division is selected
            if($request->has('division_id') ){
                // get divisions of that division_id
                $divisionsIDs = Division::descendantsAndSelf($request->division_id)->pluck('id');
                // get users of those divisions
                $users = User::whereIn('division_id', $divisionsIDs)->get();
                // get user ids
                $userIDs = $users->pluck('id');
                // timesets that belongs to those users
                $timesetQuery->whereHas('task', function (Builder $task) use ($userIDs) {
                    $task->whereIn('responsible_id', $userIDs);
                });
            }
            else{
                // get all users
                $users = User::alone()->get();
            }
        }
        // execute query
        $timesets = $timesetQuery->get();

        return compact('users', 'timesets');
    }

    private function isExeption()
    {
        // Get exeptions
        $exeptions = Division::whereIn('abbreviation', ['ОРПО', 'ОУПС', 'Evolet'])->get()->pluck('id');
        // Get auth user
        $auth = auth()->user();

        return $exeptions->contains($auth->division_id);
    }
}
