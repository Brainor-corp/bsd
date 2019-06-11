<?php

namespace App\Http\Controllers;

use App\InsideForwarding;
use App\Route;

class PricesController extends Controller {

    public function pricesPage() {
        $shipCity = '53';
        $destCity = '78';

        $route = Route::where([
            ['ship_city_id', $shipCity],
            ['dest_city_id', $destCity]
        ])->with('route_tariffs.rate', 'route_tariffs.threshold')->first();

        $insideForwardings = InsideForwarding::with('forwardThreshold', 'city')
            ->has('forwardThreshold')
            ->whereIn('city_id', [$shipCity, $destCity])
            ->get();

	    return view('v1.pages.prices.prices-page')
            ->with(compact('route','insideForwardings', 'baseRoute'));
    }

}
