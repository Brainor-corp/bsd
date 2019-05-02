<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class NeedPassReset
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
    	if(isset(Auth::user()->id)){
		    $user = User::whereId(Auth::user()->id)->first();
	    }

        if (isset($user) && $user->need_password_reset){
	        return redirect('profile');
        }

        return $next($request);
    }
}
