<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Helpers\CalculatorHelper;
use App\Order;
use App\Oversize;
use App\OversizeMarkup;
use App\Point;
use App\Route;
use App\Service;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalculatorController extends Controller
{
    public function calculatorShow($id = null, Request $request) {
        // Если имеем дело с незавершенным заказом
        if($request->has('continue') && session()->has('process_order')) {
            $continueOrder = json_decode(session()->get('process_order'));
            $continueOrder = (array)$continueOrder;
            $continueOrder['cargo'] = (array)$continueOrder['cargo'];
            $continueOrder['cargo']['packages'] = (array)$continueOrder['cargo']['packages'];
            $continueOrder['cargo']['packages'] = array_map(function ($el) {
                return (array)$el;
            }, $continueOrder['cargo']['packages']);

            // удалим незавершенный заказ из сессии
            session()->forget('process_order');

            // направим пользователя на дооформление заказа с проставленными полями
            return redirect(route('calculator-show', [
               'id' => $continueOrder['order_id'] ?? null
            ]))->withInput($continueOrder);
        }

        $shipCities = City::where('is_ship', true)->with('terminal','kladr')->get();

        $citiesIdsToFindRoute = [];
        $order = null;

        if(isset($id)) { // Если открыли страницу черновика
            $order = Order::where('id', $id)
                ->when(
                    Auth::check(),
                    function ($orderQuery) {
                        return $orderQuery->where(function ($orderSubQuery) {
                            return $orderSubQuery->where('user_id', Auth::user()->id)
                                ->orWhere('enter_id', $_COOKIE['enter_id']);
                        });
                    }
                )
                ->when(
                    !Auth::check(),
                    function ($orderQuery) {
                        return $orderQuery->where('enter_id', $_COOKIE['enter_id']);
                    }
                )
                ->with([
                    'order_items',
                    'order_services',
                    'ship_city',
                    'dest_city',
                ])
                ->firstOrFail();

            $packages = $order->order_items->toArray();
            $selectedShipCity = $order->ship_city_id;
            $selectedDestCity = $order->dest_city_id;

            $citiesIdsToFindRoute['ship'] = [
                $shipCities->where('id', $order->ship_city_id)->first()
            ];

            $citiesIdsToFindRoute['bring'] = [
                City::where('id', $order->dest_city_id)->first()
            ];
        } else { // Если открыли стандартный калькулятор
            if(isset($request->cargo['packages'])){
                $requestPackages = $request->cargo['packages'];
                foreach ($requestPackages as $key=>$package){

                    $packages[$key]=[
                        'length' => floatval($package['length'] ?? '0.1'),
                        'width' => floatval($package['width'] ?? '0.1'),
                        'height' => floatval($package['height'] ?? '0.1'),
                        'weight' => floatval($package['weight'] ?? '1'),
                        'volume' => floatval($package['volume'] ?? '0.001'),
                        'quantity' => floatval($package['quantity'] ?? '1')
                    ];
                    if($packages[$key]['length'] * $packages[$key]['width'] * $packages[$key]['height'] !== $package['volume']){
                        $packages[$key]['height'] = 2;
                        $packages[$key]['width'] = $package['volume']/2;
                        $packages[$key]['length'] = 1;
                    }
                }
            }else{
                $packages=[
                    1=>[
                        'length' => '0.1',
                        'width' => '0.1',
                        'height' => '0.1',
                        'weight' => '1',
                        'volume' => '0.001',
                        'quantity' => '1'
                    ]
                ];
            }


            $selectedShipCity = null;
            $selectedDestCity = null;

            if(!empty(old('ship_city'))) { // Если форма не прошла проверку валидации, берём заполненные города
                $request->merge([
                    'ship_city' => old('ship_city')
                ]);
            }

            if(!empty(old('dest_city'))) { // Если форма не прошла проверку валидации, берём заполненные города
                $request->merge([
                    'dest_city' => old('dest_city')
                ]);
            }

            $deliveryPoint = null;
            if(isset($request->ship_city)){ // Если город отправления выбран
                $selectedShipCity = City::where('name', $request->ship_city)->first(); // Пробуем найти его в таблице городов по названию
                $deliveryPoint = Point::where('name', $request->ship_city)->first(); // Пробуем найти его в таблице пунктов
            } else { // Если город отправления не выбран
                $selectedShipCity = City::where('id', 53)->first(); // Пробуем найти его в таблице городов по конкретному id (53 -- Москва)
            }

            $citiesIdsToFindRoute['ship'] = [
                $selectedShipCity, $deliveryPoint
            ];

            $bringPoint = null;
            if(isset($request->dest_city)){ // Если город отправления выбран
                $selectedDestCity = City::where('name', $request->dest_city)->first(); // Пробуем найти его в таблице городов по названию
                $bringPoint = Point::where('name', $request->dest_city)->first(); // Пробуем найти его в таблице пунктов
            } else { // Если город отправления не выбран
                $selectedDestCity = City::where('id', 78)->first(); // Пробуем найти его в таблице городов по конкретному id (78 -- Санкт-Петербург)
            }

            $citiesIdsToFindRoute['bring'] = [
                $selectedDestCity, $bringPoint
            ];
        }

        $route = null;

        // В базе могут существовать города и пункты с одинаковым названием.
        // Из-за этого может оказаться, что для города маршрута нет,
        // в то время как для пункта с таким же названием он есть (Прим.: Москва -> Адлер).
        // Поэтому для поиска маршрута проходим циклом по всем найденный городам/пунктам.
        foreach($citiesIdsToFindRoute['ship'] as $shipModel) {
            if(!isset($shipModel)) {
                continue;
            }

            $selectedShipCity = $shipModel instanceof City ? $shipModel->id : $shipModel->city_id;

            foreach($citiesIdsToFindRoute['bring'] as $destModel) {
                if(!isset($destModel)) {
                    continue;
                }

                $selectedDestCity = $destModel instanceof City ? $destModel->id : $destModel->city_id;

                $route = Route::where([
                    ['ship_city_id', $selectedShipCity],
                    ['dest_city_id', $selectedDestCity],
                ])->first();

                if(isset($route)) {
                    break;
                }
            }
        }

        if(!isset($route)) {
            return abort(404, "Route not found");
        }

        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $selectedShipCity))
            ->with('terminal')
            ->orderBy('name')
            ->get();

        $tariff = json_decode($this->getTariff($request, $packages ?? null, $selectedShipCity,$selectedDestCity)->content());

        $services = Service::get();
        $userTypes = Type::where('class', 'UserType')->get();

        $cargoTypes = Type::where('class', 'cargo_type')->get();

        $oversizeMarkups = OversizeMarkup::get();

        return view('v1.pages.calculator.calculator-show.calculator-show')
            ->with(compact(
                'packages',
                'shipCities',
                'destinationCities',
                'route',
                'tariff',
                'services',
                'selectedShipCity',
                'selectedDestCity',
                'order',
                'userTypes',
                'cargoTypes',
                'oversizeMarkups'
            ));

    }

    public function getAllCalculatedData(Request $request) {
        $cities = City::whereIn('name', [
            $request->get('ship_city'),
            $request->get('dest_city')
        ])->with([
            'polygons' => function ($polygonsQ) {
                return $polygonsQ->orderBy('priority');
            }
        ])->get();

        if(count($cities) < 2) {
            return [
                "error" => "Cities not found",
                "data" => $request->all()
            ];
        }

        $totalWeight = 0;
        $totalVolume = 0;

        foreach($request->get('cargo')['packages'] as $package) {
            $totalWeight += $package['weight'] * $package['quantity'];
            $totalVolume += $package['volume'] * $package['quantity'];
        }

        return CalculatorHelper::getAllCalculatedData(
            $cities->where('name', $request->get('ship_city'))->first(),
            $cities->where('name', $request->get('dest_city'))->first(),
            $request->get('cargo')['packages'],
            $request->get('service'),
            $request->get('need-to-take') === "on" ?
            [
                'cityName' => $request->get('need-to-take-type') === "in" ?
                    $cities->where('name', $request->get('ship_city'))->first()->name :
                    $request->get('take_city_name'),
                'weight' => $totalWeight,
                'volume' => $totalVolume,
                'isWithinTheCity' => $request->get('need-to-take-type') === "in",
                'x2' => $request->get('ship-from-point') === "on",
                'distance' => $request->get('take_distance'),
                'polygonId' => $request->get('take_polygon')
            ] : [],
            $request->get('need-to-bring') === "on" ?
            [
                'cityName' => $request->get('need-to-bring-type') === "in" ?
                    $cities->where('name', $request->get('dest_city'))->first()->name :
                    $request->get('bring_city_name'),
                'weight' => $totalWeight,
                'volume' => $totalVolume,
                'isWithinTheCity' => $request->get('need-to-bring-type') === "in",
                'x2' => $request->get('bring-to-point') === "on",
                'distance' => $request->get('bring_distance'),
                'polygonId' => $request->get('bring_polygon')
            ] : [],
            $request->get('insurance_amount'),
            $request->get('discount')
        );
    }

    public function getDestinationCities(Request $request, $ship_city = null) {

        if($ship_city == null){
            $ship_city = $request->ship_city;
        }

        if(!is_numeric($ship_city)) {
            $query = $ship_city;

            $ship_city = City::where('name', $query)->first();
            if(empty($ship_city)) {
                $ship_city = Point::where('name', $query)->firstOrFail();
            }

            $ship_city = $ship_city instanceof City ? $ship_city->id : $ship_city->city_id;
        }

        if(empty($ship_city)) {
            return [];
        }

        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $ship_city))
            ->with('terminal','kladr')
            ->orderBy('name')
            ->get();

        if($request->has('pointsNeed')) {
            $destinationPoints = Point::whereHas('city', function ($cityQ) use ($ship_city) {
                return $cityQ->whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $ship_city));
            })->with([
                'city.terminal',
                'city.kladr',
            ])->get();

            $merged = Collect();
            foreach($destinationCities as $destinationCity) {
                $city = new \stdClass();
                $city->name = $destinationCity->name;
                $city->terminal = $destinationCity->terminal;
                $city->kladr = $destinationCity->kladr;
                $city->coordinates_or_address = $destinationCity->coordinates_or_address;

                $merged->push($city);
            }
            foreach($destinationPoints as $destinationPoint) {
                $point = new \stdClass();
                $point->name = $destinationPoint->name;
                $point->terminal = $destinationPoint->city->terminal;
                $point->kladr = $destinationPoint->city->kladr;
                $point->coordinates_or_address = $destinationPoint->city->coordinates_or_address;

                $merged->push($point);
            }

            $destinationCities = $merged->sortBy('name')->unique('name');
        }

        return view('v1.pages.calculator.parts.destination-cities')->with(compact('destinationCities'));
    }

    public function getRoute(Request $request, $ship_city = null, $dest_city = null) {

        if($ship_city == null){
            $ship_city = $request->ship_city;
        }
        if($dest_city == null){
            $dest_city = $request->dest_city;
        }
        $route = Route::where('ship_city_id', $ship_city)
            ->where('dest_city_id', $dest_city)
            ->first();

        return $route;
    }

    public function getOversize(Request $request) {

        $oversize = Oversize::where('id', 1)->first();

        return $oversize;
    }

    public function oversize_ratio($oversize_id, $package) {
        return CalculatorHelper::oversize_ratio($package);
    }

    public function getTariff(Request $request, $packages = null, $ship_city = null, $dest_city = null, $route_id = null) {
        $formData = null;
        if(isset($request->formData)){
            $formData = array();
            parse_str($request->formData, $formData);
        }

        if($ship_city == null){$ship_city = $request->ship_city;}
        if($dest_city == null){$dest_city = $request->dest_city;}

        $citiesIdsToFindRoute = [];

        $shipCity = null;
        $deliveryPoint = null;
        if(is_numeric($ship_city)) {
            $shipCity = City::where('id', $ship_city)->first();
            $deliveryPoint = Point::where('id', $ship_city)->first();
        } else {
            $shipCity = City::where('name', $ship_city)->first();
            $deliveryPoint = Point::where('name', $ship_city)->first();
        }

        $citiesIdsToFindRoute['ship'] = [
            $shipCity, $deliveryPoint
        ];

        $destCity = null;
        $bringPoint = null;
        if(is_numeric($dest_city)) {
            $destCity = City::where('id', $dest_city)->first();
            $bringPoint = Point::where('id', $dest_city)->first();
        } else {
            $destCity = City::where('name', $dest_city)->first();
            $bringPoint = Point::where('name', $dest_city)->first();
        }

        $citiesIdsToFindRoute['bring'] = [
            $destCity, $bringPoint
        ];

        if($route_id == null){
            if(!isset($request->route_id)){
                // В базе могут существовать города и пункты с одинаковым названием.
                // Из-за этого может оказаться, что для города маршрута нет,
                // в то время как для пункта с таким же названием он есть (Прим.: Москва -> Адлер).
                // Поэтому для поиска маршрута проходим циклом по всем найденный городам/пунктам.
                foreach($citiesIdsToFindRoute['ship'] as $shipModel) {
                    if(!isset($shipModel)) {
                        continue;
                    }

                    $ship_city = $shipModel instanceof City ? $shipModel->id : $shipModel->city_id;

                    foreach($citiesIdsToFindRoute['bring'] as $destModel) {
                        if(!isset($destModel)) {
                            continue;
                        }

                        $dest_city = $destModel instanceof City ? $destModel->id : $destModel->city_id;

                        $route = Route::where([
                            ['ship_city_id', $ship_city],
                            ['dest_city_id', $dest_city],
                        ])->first();

                        if(isset($route)) {
                            $route_id = $route->id;
                            break;
                        }
                    }
                }

                if(!isset($route_id)) {
                    return abort(404, "Route not found");
                }
            }else{
                $route_id = $request->route_id;
            }
        }

        if($packages == null){
            if(is_array($request->packages)){
                $packages = $request->cargo['packages'];
            }else{
                $packages = array();
                parse_str($request->formData, $packages);
                $packages = $packages['cargo']['packages'];
            }
        }

        $weight = $formData['cargo']['packages'][0]['weight'];
        $volume = $formData['cargo']['packages'][0]['volume'];

        $services = null;
        if(isset($formData['service'])){$services = $formData['service'];}

        $routeData = CalculatorHelper::getRouteData(null, null, $packages, $route_id);
        $deliveryData = isset($deliveryPoint) ? CalculatorHelper::getDeliveryToPointPrice($deliveryPoint, $weight, $volume) : null;
        $bringData = isset($bringPoint) ? CalculatorHelper::getDeliveryToPointPrice($bringPoint, $weight, $volume) : null;

        $totalData = CalculatorHelper::getTotalPrice(
            $routeData['price'],
            $services,
            $routeData['totalWeight'],
            $routeData['totalVolume'],
            null,
            null,
            $deliveryData['price'] ?? null,
            $bringData['price'] ?? null
        );

        $resultData = [
            'base_price' => $routeData['price'],
            'total_volume' => $routeData['totalVolume'],
            'route' => $routeData['model'],
            'total_data' => $totalData,
            'delivery_to_point' => $deliveryData,
            'bring_to_point' => $bringData,
        ];

        return response()->json($resultData);
    }

    public function getTotalPrice(Request $request, $base_price = null, $totalVolume = null, $totalWeight = null, $needJson = true) {
        $take_price = $bring_price = $insuranceAmount = $discount = $formData = null;

        if(isset($request->base_price)){$base_price = $request->base_price;}
        if($base_price == null){
            $totalPrice = $request->base_price ?? 0;
        }else{
            $totalPrice = $base_price;
        }

        if(isset($request->formData)){
            $formData = array();
            parse_str($request->formData, $formData);
        }

        $services = null;

        if(isset($request->service)){$services = $request->service;}
        if(isset($formData['service'])){$services = $formData['service'];}

        if(isset($request->insurance_amount)){$insuranceAmount = $request->insurance_amount;}
        if(isset($formData['insurance_amount'])){$insuranceAmount = $formData['insurance_amount'];}

        if(isset($request->discount)){$discount = $request->discount;}
        if(isset($formData['discount'])){$discount = $formData['discount'];}

        if(isset($request->total_volume)){$totalVolume = $request->total_volume;}
        if(isset($formData['total_volume'])){$totalVolume = $formData['total_volume'];}
        if($totalVolume == null){$totalVolume = 1;}
        if($totalWeight == null){$totalWeight = 1;}

        if(is_numeric($totalPrice)) {
            if($request->get('take_price') && is_numeric($request->get('take_price'))) {$take_price = floatval($request->get('take_price'));}
            if($request->get('bring_price') && is_numeric($request->get('bring_price'))) {$bring_price = floatval($request->get('bring_price'));}
        }

        $result = CalculatorHelper::getTotalPrice(
            $base_price,
            $services,
            $totalVolume,
            $totalWeight,
            $insuranceAmount,
            $discount,
            $take_price,
            $bring_price
        );

        if($needJson == true){
            return response()->json($result);
        }else{
            return $result;
        }
    }

    public function getCityPolygons(Request $request) {
        $city = City::where('name', $request->get('city'))
            ->with([
                'polygons' => function ($polygonsQ) {
                    return $polygonsQ->orderBy('priority');
                }
            ])->first();

        return $city->polygons ?? [];
    }
}
