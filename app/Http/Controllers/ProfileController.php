<?php

namespace App\Http\Controllers;

use App\Position;
use App\Status;
use App\Task;
use App\Timeset;
use App\User;
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
        
        if (\Auth::user()->positionLevel->name == "Руководитель" && \Auth::user()->division->id == $user->division->id) {
            $editable = true;
        }

        return view('profile.positions', compact('user','editable'));
    }

    public function tasks($userID)
    {
        $closedStatus = Status::where('name', 'Закрытый')->first();

        $timesets = Timeset::whereHas('task', function(Builder $taskQuery) use ($userID){
                                $taskQuery->where('responsible_id', $userID);
                            })
                            ->with('task.responsibilityDescription')->get();

        $tasks = Task::whereHas('timeSets')
                     ->where('status_id',$closedStatus->id)
                     ->where('responsible_id',$userID)
                     ->with('timeSets','responsibilityDescription')
                     ->get();

        return view('profile.tasks', compact('timesets','tasks'));
    }

    public function dashboard($id)
    {
        $statuses = Status::withCount(['tasks' => function(Builder $query) use ($id){
            $query->where('responsible_id', $id);
        }])->get();

        return view('users.show', compact('statuses'));
    }
}
