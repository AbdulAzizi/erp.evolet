<?php

namespace App\Http\Controllers;

use App\Entry;
use App\Status;
use App\Task;
use App\Timeset;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function positions(Request $request, $id)
    {
        $user = User::with([
            'division.positions.responsibilities',
        ])->with(['positions' => function ($positionQuery) use ($id) {
            $positionQuery->with(['responsibilities' => function ($responsibilityQuery) use ($id) {
                $responsibilityQuery->with('descriptions')->whereHas('users', function (Builder $userQuery) use ($id) {
                    $userQuery->where('users.id', $id);
                });
            }]);
        }])->find($id);

        $editable = false;

        if (
            (\Auth::user()->positionLevel->name == "Руководитель" && \Auth::user()->division->id == $user->division->id) || \Auth::user()->fullname == 'Абдулазиз Нуров') {
            $editable = true;
        }

        return view('profile.positions', compact('user', 'editable'));
    }

    public function tasks($userID)
    {
        $request = new Request([
            'userID' => $userID,
            'from' => Carbon::now()->startOfDay(),
            'to' => Carbon::now()->endOfDay(),
        ]);

        $timesets = $this->getTimeSets($request);
        $tasks = $this->getTasks($request);

        return view('profile.tasks', compact('timesets', 'tasks'));
    }

    public function dashboard($id)
    {
        $statuses = Status::withCount(['tasks' => function (Builder $query) use ($id) {
            $query->where('responsible_id', $id);
        }])->get();

        return view('users.show', compact('statuses'));
    }

    public function getTasks(Request $request)
    {
        $closedStatus = Status::where('name', 'Закрытый')->first();

        $from = new Carbon($request->from);
        $from = $from->startOfDay();

        $to = new Carbon($request->to);
        $to = $to->endOfDay();

        $tasks = Task::where('status_id', $closedStatus->id)
            ->where('responsible_id', $request->userID)
            ->where('end_time', '>=', $from)
            ->where('end_time', '<=', $to)
            ->with('timeSets', 'responsibilityDescription')
            ->get();

        return $tasks;
    }

    public function getTimeSets(Request $request)
    {
        $userID = $request->userID;

        $from = new Carbon($request->from);
        $from = $from->startOfDay();

        $to = new Carbon($request->to);
        $to = $to->endOfDay();

        $timesets = Timeset::whereHas('task', function (Builder $taskQuery) use ($userID) {
            $taskQuery->where('responsible_id', $userID);
        })
            ->where('end_time', '>=', $from)
            ->where('end_time', '<=', $to)
            ->with('task.responsibilityDescription')
            ->get();

        return $timesets;
    }

    public function setTasks($id)
    {
        $fromTasks = Task::where('responsible_id', $id)->with('responsibilityDescription', 'from', 'responsible', 'tags', 'status')->get()->groupBy('from_id');

        $toTasks = Task::where('from_id', $id)->with('responsibilityDescription', 'from', 'responsible', 'tags', 'status')->get()->groupBy('responsible_id');

        return view('users.setTasks', compact('fromTasks', 'toTasks'));
    }

    public function entries($id)
    {
        $entries = Entry::where('user_id', $id)->with('user')->get();
        return view('users.entries', compact('entries'));
    }
}
