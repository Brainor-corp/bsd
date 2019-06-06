<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Route;
use App\RouteTariff;

class PricesController extends Controller {

    public function pricesPage() {
        $shipCity = '53';
        $destCity = '78';

        $route = app('App\Http\Controllers\CalculatorController')->getRoute(null,$shipCity,$destCity);

        if(null !== $route->base_route){
            $baseRoute = Route::where('id', $route->base_route)->first();
        }
        $routeTariffs = RouteTariff::with('rate','threshold')
            ->where('route_id', $route->id)
            ->orderBy('price', 'ASC')
            ->get()
            ->groupBy('rate_id');


//        dd($routeTariffs);

	    return view('v1.pages.prices.prices-page')->with(compact('route','routeTariffs', 'baseRoute'));
    }

}
