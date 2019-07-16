<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Helpers\CalculatorHelper;
use App\Order;
use App\Oversize;
use App\Point;
use App\Route;
use App\Service;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalculatorController extends Controller
{
    public function calculatorShow($id = null, Request $request) {
        $shipCities = City::where('is_ship', true)->with('terminal','kladr')->get();

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
            if(isset($request->ship_city)){
                $selectedShipCity = $request->ship_city;
            }else{
                $selectedShipCity = 53;
            }

            if(isset($request->ship_city) && isset($request->dest_city)){
                $selectedDestCity = $request->dest_city;
            }else{
                $selectedDestCity = 78;
            }
        }

        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $selectedShipCity))
            ->with('terminal')
            ->orderBy('name')
            ->get();

        $route = $this->getRoute($request, $selectedShipCity,$selectedDestCity);
        $tariff = json_decode($this->getTariff($request, $packages, $selectedShipCity,$selectedDestCity)->content());

        $services = Service::get();
        $userTypes = Type::where('class', 'UserType')->get();

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
                'userTypes'
            ));

    }

    public function getAllCalculatedData(Request $request) {
        $cities = City::whereIn('id', [
            $request->get('ship_city'),
            $request->get('dest_city')
        ])->with([
            'polygons' => function ($polygonsQ) {
                return $polygonsQ->orderBy('priority');
            }
        ])->get();

        if(count($cities) < 2) {
            return ["error" => "error"];
        }

        $totalWeight = 0;
        $totalVolume = 0;

        foreach($request->get('cargo')['packages'] as $package) {
            $totalWeight += $package['weight'];
            $totalVolume += $package['volume'];
        }

        return CalculatorHelper::getAllCalculatedData(
            $cities->where('id', $request->get('ship_city'))->first(),
            $cities->where('id', $request->get('dest_city'))->first(),
            $request->get('cargo')['packages'],
            $request->get('service'),
            $request->get('need-to-take') === "on" ?
            [
                'cityName' => $request->get('need-to-take-type') === "in" ?
                    $cities->where('id', $request->get('ship_city'))->first()->name :
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
                    $cities->where('id', $request->get('dest_city'))->first()->name :
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
                $ship_city = Point::where('name', $query)->first();
            }

            $ship_city = $ship_city instanceof City ? $ship_city->id : ($ship_city->city_id ?? null);
        }

        if(empty($ship_city)) {
            return [];
        }

        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $ship_city))
            ->with('terminal','kladr')
            ->orderBy('name')
            ->get();

        $destinationPoints = Point::whereHas('city', function ($cityQ) use ($ship_city) {
            return $cityQ->where('id', $ship_city);
        })->with([
            'city.terminal',
            'city.kladr',
        ])->get();

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
        
        $shipCity = null;
        if(is_numeric($ship_city)) {
            $shipCity = City::where('id', $ship_city)->first();
            if(empty($shipCity)) {
                $shipCity = Point::where('id', $ship_city)->firstOrFail();
            }
        } else {
            $shipCity = City::where('name', $ship_city)->first();
            if(empty($shipCity)) {
                $shipCity = Point::where('name', $ship_city)->firstOrFail();
            }
        }
        $ship_city = $shipCity instanceof City ? $shipCity->id : $shipCity->city_id;

        $destCity = null;
        if(is_numeric($dest_city)) {
            $destCity = City::where('id', $dest_city)->first();
            if(empty($destCity)) {
                $destCity = Point::where('id', $dest_city)->firstOrFail();
            }
        } else {
            $destCity = City::where('name', $dest_city)->first();
            if(empty($destCity)) {
                $destCity = Point::where('name', $dest_city)->firstOrFail();
            }
        }
        $dest_city = $destCity instanceof City ? $destCity->id : $destCity->city_id;

        if($route_id == null){
            if(!isset($request->route_id)){
                $route = Route::where([
                    ['ship_city_id', $ship_city],
                    ['dest_city_id', $dest_city],
                ])->first();
                $route_id = $route->id;
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

        $services = null;
        if(isset($formData['service'])){$services = $formData['service'];}

        $routeData = CalculatorHelper::getRouteData(null, null, $packages, $route_id);
        $totalData = CalculatorHelper::getTotalPrice($routeData['price'], $services, $routeData['totalVolume']);
        $resultData = [
            'base_price' => $routeData['price'],
            'total_volume' => $routeData['totalVolume'],
            'route' => $routeData['model'],
            'total_data' => $totalData,
        ];

        return response()->json($resultData);
    }

    public function getTotalPrice(Request $request, $base_price = null, $totalVolume = null, $needJson = true) {
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

        if(is_numeric($totalPrice)) {
            if($request->get('take_price') && is_numeric($request->get('take_price'))) {$take_price = floatval($request->get('take_price'));}
            if($request->get('bring_price') && is_numeric($request->get('bring_price'))) {$bring_price = floatval($request->get('bring_price'));}
        }

        $result = CalculatorHelper::getTotalPrice($base_price, $services, $totalVolume, $insuranceAmount, $discount, $take_price, $bring_price);

        if($needJson == true){
            return response()->json($result);
        }else{
            return $result;
        }
    }

    public function getCityPolygons(Request $request) {
        $city = City::where('id', intval($request->get('city_id')))
            ->with([
                'polygons' => function ($polygonsQ) {
                    return $polygonsQ->orderBy('priority');
                }
            ])->first();

        return $city->polygons ?? [];
    }
}
