<?php

namespace App\Http\Middleware;

use Closure;

class IpCheck
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
        if(empty(env('API_ALLOW_IP')) || env('API_ALLOW_IP') == "*") {
            return $next($request);
        }

        if (!in_array($request->ip(), explode(',', env('API_ALLOW_IP')))) {
            return response(['Not authorized.'],403);
        }

        return $next($request);
    }
}
