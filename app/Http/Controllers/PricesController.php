<?php

namespace App\Http\Controllers;

use App\City;
use App\InsideForwarding;
use App\Route;
use Illuminate\Http\Request;

class PricesController extends Controller {

    public function pricesPage(Request $request) {
        $shipCityId = $request->get('ship_city') ?? 53;
        $destCityId = $request->get('dest_city') ?? 78;

        $route = Route::where([
            ['ship_city_id', $shipCityId],
            ['dest_city_id', $destCityId]
        ])->with('route_tariffs.rate', 'route_tariffs.threshold')->first();

        $insideForwardings = InsideForwarding::with('forwardThreshold', 'city')
            ->has('forwardThreshold')
            ->whereIn('city_id', [$shipCityId, $destCityId])
            ->get();

        $shipCities = City::where('is_ship', true)->get();
        $destCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $shipCityId))->get();

	    return view('v1.pages.prices.prices-page')
            ->with(compact('route','insideForwardings', 'shipCities', 'destCities', 'shipCityId', 'destCityId'));
    }

    public function getDestinationCities(Request $request) {
        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $request->get('ship_city')))
            ->orderBy('name')
            ->get();

        return view('v1.partials.prices.destination-cities')->with(compact('destinationCities'));
    }

}
