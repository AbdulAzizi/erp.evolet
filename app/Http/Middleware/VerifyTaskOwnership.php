<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class VerifyTaskOwnership
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
        $response = $next($request);
        // Check if page Exists
        if ($response->status() != 404) {
            // Perform action after controller
            // get task from response
            $task = $response->original->__get('task');
            //get auth user
            $authUser = auth()->user();
            // gather watchers
            $allowedUserIDs = $task->watchers->pluck('id');
            // add responsible
            $allowedUserIDs[] = $task->responsible->id;
            // add assignee if it is a user
            if ($task->from_type == 'App\User') {
                $allowedUserIDs[] = $task->from->id;
                // if division has a head
                if ($task->from->division->head) {
                    // add division head id
                    $allowedUserIDs[] = $task->from->division->head->id;
                }
            }
            // if division has a head
            if ($task->responsible->division->head) {
                // add division head id
                $allowedUserIDs[] = $task->responsible->division->head->id;
            }
            // add HR and RVZ users as exceptions
            $respExceptions = User::whereHas('positions', function (Builder $query) {
                $query->where('name', 'HR')
                    ->orWhere('name', 'РВЗ');
            })->pluck('id');
            // merge the result of query with existing array
            $allowedUserIDs = $allowedUserIDs->merge($respExceptions);

            // if auth user is have a relation with task
            if ($allowedUserIDs->contains($authUser->id)) {
                // continue
                return $response;
            } else {
                // redirect to all tasks
                return redirect()->route('tasks.index');
            }
        } else {
            // continue
            return $response;
        }
    }
}
