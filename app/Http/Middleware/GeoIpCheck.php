<?php

namespace App\Http\Middleware;

use App\City;

use \Torann\GeoIP\Facades\GeoIP;

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


        if($request->session()->has('current_city')) {//если задана сессия с городом - ничего не делаем
//            $sessionCity = $request->session()->get('current_city');
//            if(is_numeric($sessionCity['current_city']['id'])) {
//            }

        }else{//если сессия не задана
            if(isset($_COOKIE['current_city'])) {//если задана кука - копируем ее в сессию
                $cookieCity = unserialize($_COOKIE['current_city']);
                if(is_numeric($cookieCity['id'])) {
                    $request->session()->put('current_city', $cookieCity);
                }
            }else{//если кука не задана - получаем город по ip, ищем в базе - задаем куку и сессию.
                $ip = geoip()->getLocation('37.190.39.0');

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
