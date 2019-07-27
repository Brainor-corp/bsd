<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OrderSave
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
        if($request->get('status') !== "chernovik" && !Auth::check()) {
            session()->put('process_order', json_encode($request->all()));
            session()->put('process_order_modal', 1);

            return redirect(route('login'));
        }

        return $next($request);
    }
}
