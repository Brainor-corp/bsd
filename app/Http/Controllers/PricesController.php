<?php

namespace App\Http\Controllers;

use App\City;
use App\InsideForwarding;
use App\Route;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class PricesController extends Controller {
    public function pricesPage(Request $request) {
        $shipCityIds = $request->get('ship_city') ?? [53];
        $destCityIds = $request->get('dest_city') ?? [78];

        $routesIdsPairs = [];
        foreach($shipCityIds as $shipCityId) {
            foreach($destCityIds as $destCityId) {
                $routesIdsPairs[] = [
                    ['ship_city_id', $shipCityId],
                    ['dest_city_id', $destCityId]
                ];
            }
        }

        $routes = Route::where(function ($routesQuery) use ($routesIdsPairs) {
            foreach($routesIdsPairs as $idsPair) {
                $routesQuery->orWhere($idsPair);
            }

            return $routesQuery;
        })->with('route_tariffs.rate', 'route_tariffs.threshold')->get();

        $insideForwardings = InsideForwarding::with('forwardThreshold', 'city')
            ->has('forwardThreshold')
            ->whereIn('city_id', array_merge($shipCityIds, $destCityIds))
            ->get();

        $action = $request->get('action') ?? 'show';

        if($action == 'show') {
            $shipCities = City::where('is_ship', true)->get();
            $destCities = City::whereIn('id', Route::select(['dest_city_id'])->whereIn('ship_city_id', $shipCityIds))->get();

            return view('v1.pages.prices.prices-page')
                ->with(compact('routes','insideForwardings', 'shipCities', 'destCities', 'shipCityIds', 'destCityIds'));
        } else {
            $documentName = "Прайс-лист.pdf";
            $pdf = PDF::loadView('v1.pages.prices.pdf', [
                'routes' => $routes,
                'insideForwardings' => $insideForwardings,
                'shipCityIds' => $shipCityIds,
                'destCityIds' => $destCityIds
            ]);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->download('Прайс-лист.pdf');
        }
    }

    public function getDestinationCities(Request $request) {
        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->whereIn('ship_city_id', $request->get('ship_city')))
            ->orderBy('name')
            ->get();

        return view('v1.partials.prices.destination-cities')->with(compact('destinationCities'));
    }

}
