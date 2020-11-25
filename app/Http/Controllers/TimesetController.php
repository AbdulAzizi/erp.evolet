<?php

namespace App\Http\Controllers;

use App\User;
use App\Entry;
use App\Timeset;
use App\Division;
use App\Status;
use Illuminate\Http\Request;
use App\Filters\TimesetFilters;
use Illuminate\Database\Eloquent\Builder;

class TimesetController extends Controller
{
    public function index()
    {
        $divisions = collect([]);

        if ($this->isExeption()) {
            $divisions = Division::whereHas('users')->get();
            $users = User::all();
        } else {
            // get divisions of auth
            $divisionsIDs = Division::descendantsAndSelf(auth()->user()->division_id)->pluck('id');
            // get users of those divisions
            $users = User::whereIn('division_id', $divisionsIDs)->get();
        }

        return view('timesets.index', compact('divisions', 'users'));
    }

    public function getTimesets(Request $request, TimesetFilters $filters)
    {
        $data = [];

        $data['timesets'] = Timeset::filter($filters)
            ->with('task')
            ->get();

        $result = explode("-", $request->month);
        $year = $result[0];
        $month = $result[1];

        if ($request->has('division_id')) {

            $users = TimesetFilters::getUsers()->pluck('id');

        } else {
            $users = [$request->user_id];
        }

        $data['entries'] = Entry::whereIn('user_id', $users)
            ->whereYear('date', '=', $year)
            ->whereMonth('date', '=', $month)
            ->with(['user' => function ($query) {
                $query->without(['positionLevel', 'positions']);
            }])
            ->orderBy('user_id')
            ->orderBy('date')
            ->get();
        
        $data['users'] = User::alone()
        ->withCount(['tasks as new' => function (Builder $query) use ($month, $year) {
            $query
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->whereHas('status', function (Builder $query) {
                    $query->where('name', 'Новый');
                });
        }])
        ->withCount(['tasks as in_proccess' => function (Builder $query) use ($month, $year) {
            $query
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->whereHas('status', function (Builder $query) {
                    $query->where('name', 'В процессе');
                });
        }])
        ->withCount(['tasks as paused' => function (Builder $query) use ($month, $year) {
            $query
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->whereHas('status', function (Builder $query) {
                    $query->where('name', 'Приостановлен');
                });
        }])
        ->withCount(['tasks as closed' => function (Builder $query) use ($month, $year) {
            $query
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->whereHas('status', function (Builder $query) {
                    $query->where('name', 'Закрытый');
                });
        }])
        ->find($users);
        
        return $data;
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
