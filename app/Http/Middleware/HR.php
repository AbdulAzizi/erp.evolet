<?php

namespace App\Http\Middleware;

use Closure;

class HR
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
        $isHR = false;

        foreach (auth()->user()->positions as $position) {
            if ($position->name == 'HR') {
                $isHR = true;
            }
        }
        
        if ($isHR) {
            return $next($request);
        } else {
            return redirect()->route('tasks.index');
        }
    }
}
