<?php

namespace App\Http\Middleware;

use App\Task;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\View;

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

        View::share('authUser', $authUser);
        View::share('currentTask', $currentTask);

        // $users = \App\User::with('division')->get();
        // View::share('users', $users );

        return $next($request);

    }
}
