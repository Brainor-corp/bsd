<?php

namespace App\Http\Middleware;

use App\City;
use Closure;

class GeoIpCheck
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
        if(!$request->session()->has('current_city')) {//если не задана сессия с городом
            if(isset($_COOKIE['current_city'])) {//если задана кука - копируем ее в сессию
                $cookieCity = unserialize($_COOKIE['current_city']);
                if(is_numeric($cookieCity['id'])) {
                    $request->session()->put('current_city', $cookieCity);
                }
            } else {//если кука не задана - получаем город по ip, ищем в базе - задаем куку и сессию.
                $ip = geoip()->getLocation($request->ip());

                $city = City::where('name', $ip->city)
                    ->orWhere('slug', 'sankt-peterburg')
                    ->select('id', 'slug', 'name')
                    ->first();

                $cookieValue = [
                    'name'          =>   $city->name ?? '',
                    'id'            =>   $city->id ?? '',
                    'confirmed'     =>   false
                ];
                setcookie(
                    "current_city",
                    serialize($cookieValue),
                    time() + (10 * 365 * 24 * 60 * 60),
                    "/"
                );

                $request->session()->put('current_city', $cookieValue);
            }
        }

        return $next($request);
    }
}
