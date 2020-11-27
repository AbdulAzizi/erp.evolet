<?php

namespace App\Http\Middleware;

use App\Division;
use App\Task;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\View;
use \App\Request as UserRequest;

class ProvideDefaultData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authUser = auth()->user()->load([
            'division',
        ])
            ->loadCount('unreadNotifications');

        $currentTask = Task::whereHas('status', function (Builder $query) {
            $query->where('name', 'В процессе');
        })
            ->where('responsible_id', $authUser->id)
            ->with('timeSets', 'status')->first();

        // ...
        if ($currentTask) {
            $currentTask->setTimesetEndtime();
        }
        $userRequests = 0;
        if (($authUser->positions->contains('name', 'РВЗ') || $authUser->positions->contains('name', 'ОУПС'))) {
            $userRequests = UserRequest::where('user_id', '!=', $authUser->id)->where('status', 1)->count();
        } else if ($authUser->division->head_id == $authUser->id) {
            $usersID = [];
            $division = Division::find($authUser->division_id);
            foreach ($division->users as $user) {
                $usersID[] = $user->id;
            }
            $userRequests = UserRequest::whereIn('user_id', $usersID)->where('user_id', '!=', $authUser->id)->where('status', 0)->count();
        }

        View::share('authUser', $authUser);
        View::share('currentTask', $currentTask);
        View::share('userRequests', $userRequests);

        // $users = \App\User::with('division')->get();
        // View::share('users', $users );

        return $next($request);
    }
}
