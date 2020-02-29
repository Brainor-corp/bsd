<?php

namespace App\Http\Controllers;

use App\City;
use App\InsideForwarding;
use App\PerKmTariff;
use App\Route;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class PricesController extends Controller {
    public function pricesPage(Request $request) {
        $request->validate([
            'ship_city' => 'array|between:1,5',
            'ship_city.*' => 'numeric',
            'dest_city' => 'array|between:1,5',
            'dest_city.*' => 'numeric'
        ]);

        $isShowInPriceOnly = false; // Флаг ограничения выборку маршрутов по полю show_in_price
        $isToAllAvailable = true; // Флаг доступности пункта "Во всех" для городов назначения

        $shipCityIds = $request->get('ship_city') ?? [53]; // По умолчанию город отправления -- Москва
        $destCityIds = $request->get('dest_city') ?? [78]; // По умолчанию город Назначения -- Санкт-Петербург

        // Если в городе отправления выбрано "Из всех"
        if($shipCityIds[0] == 0) {
            // В качестве городов отправления выберем города, из которых есть маршруты с show_in_price = true
            $shipCityIds = Route::where('show_in_price', true)
                ->select('show_in_price', 'ship_city_id')
                ->get()
                ->pluck('ship_city_id')
                ->toArray();

            $isShowInPriceOnly = true;
            $isToAllAvailable = false;
        }

        $shipCityIds = array_unique($shipCityIds);

        // Если в городе назначения выбрано "Во все"
        if($destCityIds[0] == 0) {
            // В качестве городов назначения выберем города, в которые есть маршруты с show_in_price = true
            $destCityIds = Route::where('show_in_price', true)
                // Город отправления маршрутов должен совпадать с одним из городов отправления, выбранных ранее
                ->whereIn('ship_city_id', $shipCityIds)
                ->select('show_in_price', 'dest_city_id')
                ->get()
                ->pluck('dest_city_id')
                ->toArray();

            $isShowInPriceOnly = true;
        }

        $destCityIds = array_unique($destCityIds);

        // Соберем все полученные города в массив пар "Город отправления -> Город назначения"
        $routesIdsPairs = [];
        foreach($shipCityIds as $shipCityId) {
            foreach($destCityIds as $destCityId) {
                $routesIdsPairs[] = [
                    ['ship_city_id', $shipCityId],
                    ['dest_city_id', $destCityId]
                ];
            }
        }

        // Если есть пары "Город отправления -> Город назначения", ищем по ним маршруты
        if(count($routesIdsPairs)) {
            $routes = Route::where(function ($routesQuery) use ($routesIdsPairs) {
                foreach($routesIdsPairs as $idsPair) {
                    $routesQuery->orWhere($idsPair);
                }

                return $routesQuery;
            })
            ->when($isShowInPriceOnly, function ($routesQuery) {
                return $routesQuery->where('show_in_price', true);
            })
            ->with('route_tariffs.rate', 'route_tariffs.threshold')
            ->get();
        } else {
            $routes = null;
        }

        $insideForwardings = null;
        $perKmTariffs = null;

        // Если маршруты найдены
        if(isset($routes)) {
            // Найдём тарифы внутренней экспедиции для городов из найденных маршрутов
            $insideForwardings = InsideForwarding::with('forwardThreshold', 'city')
                ->has('forwardThreshold')
                ->whereIn('city_id', array_merge($routes->pluck('ship_city_id')->toArray(), $routes->pluck('dest_city_id')->toArray()))
                ->get();

            // Найдём покилометровые тарифы
            $perKmTariffs = PerKmTariff::whereIn('tariff_zone_id', $insideForwardings->pluck('city.tariff_zone_id')->unique()->toArray())
                ->get();
        }

        // Просмотр или скачивание
        $action = $request->get('action') ?? 'show';

        if($action == 'show') {
            $shipCities = City::where('is_ship', true)->get();

            if(!empty($request->get('ship_city')) && $request->get('ship_city')[0] == 0) {
                $destCities = City::whereIn('id', Route::select(['dest_city_id'])->where('show_in_price', true))
                    ->orderBy('name')
                    ->get();
            } else {
                $destCities = City::whereIn('id', Route::select(['dest_city_id'])->whereIn('ship_city_id', $shipCityIds))
                    ->orderBy('name')
                    ->get();
            }

            return view('v1.pages.prices.prices-page')
                ->with(compact(
                    'routes',
                    'insideForwardings',
                    'shipCities',
                    'destCities',
                    'shipCityIds',
                    'destCityIds',
                    'perKmTariffs',
                    'isToAllAvailable'
                ));
        } else {
            $pdf = PDF::loadView('v1.pages.prices.pdf', [
                'routes' => $routes,
                'insideForwardings' => $insideForwardings,
                'shipCityIds' => $shipCityIds,
                'destCityIds' => $destCityIds,
                'perKmTariffs' => $perKmTariffs
            ]);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->download('Прайс-лист.pdf');
        }
    }

    public function getDestinationCities(Request $request) {
        $isToAllAvailable = true;

        if($request->get('ship_city')[0] == 0) {
            $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('show_in_price', true))
                ->orderBy('name')
                ->get();

            $isToAllAvailable = 0;
        } else {
            $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->whereIn('ship_city_id', $request->get('ship_city')))
                ->orderBy('name')
                ->get();
        }

        return view('v1.partials.prices.destination-cities')
            ->with(compact(
                'destinationCities',
                'isToAllAvailable'
            ));
    }

}
