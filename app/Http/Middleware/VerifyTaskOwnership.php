<?php

namespace App\Http\Middleware;

use App\Task;
use Closure;

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
        }
        // get Users
        $users = $response->original->__get('users');
        // if division has a head
        if($authUser->division->head)
        // add division head id
        $allowedUserIDs[] = $authUser->division->head->id;
        // Add exception users
        // loop through all users
        $users->each(function ($user) use ($allowedUserIDs) {
            // loop through positions
            $respExceptions = $user->positions->filter(function ($position) {
                // return if it is HR or CEO
                return $position->name == "HR" || $position->name == "РВЗ";
            });
            // if has HR or CEO
            if($respExceptions->count() > 0){
                // add user to participants
                $allowedUserIDs[] = $user->id;
                // echo $allowedUserIDs;
            }
        });
        // if auth user is have a relation with task
        if ($allowedUserIDs->contains($authUser->id)) {
            // continue
            return $response;
        } else {
            // redirect to all tasks
            return redirect()->route('tasks.index');
        }
    }
}
