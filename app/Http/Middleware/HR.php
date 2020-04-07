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
        if (auth()->user()->division->abbreviation == "ОУПС") {
            return $next($request);
        } else {
            return redirect()->route('tasks.index');
        }
    }
}
