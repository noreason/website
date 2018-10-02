<?php

namespace App\Http\Middleware;

use Closure;

class Respond404IfNotModerator
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
        if (!$request->user()->is_mod) {
            abort(403);
        }

        return $next($request);
    }
}