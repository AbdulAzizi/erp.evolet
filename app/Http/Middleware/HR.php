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
        $RVZ = false;

        foreach (auth()->user()->positions as $position) {
            if ($position->name == "РВЗ") {
                $RVZ = true;
            }
        }

        if (auth()->user()->division->abbreviation == "ОУПС" || $RVZ) {
            return $next($request);
        } else {
            return redirect()->route('tasks.index');
        }
    }
}
