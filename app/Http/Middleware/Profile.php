<?php

namespace App\Http\Middleware;

use App\User;
use Illuminate\Support\Facades\View;

use Closure;

class Profile
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
        $user = User::find($request->id);
        View::share('user', $user);

        return $next($request);
    }
}
