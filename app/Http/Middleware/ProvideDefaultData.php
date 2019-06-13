<?php

namespace App\Http\Middleware;

use Closure;
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
        $authUser = auth()->user()->load(['division']);
        View::share('authUser', $authUser);

        return $next($request);

    }
}
