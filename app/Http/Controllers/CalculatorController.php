<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Helpers\CalculatorHelper;
use App\Oversize;
use App\Point;
use App\Route;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalculatorController extends Controller
{
    public function calculatorShow(Request $request) {
        if(isset($request->packages)){
            $packages = $request->packages;
        }else{
            $packages=[
              1=>[
                  'length' => '0.1',
                  'width' => '0.1',
                  'height' => '0.1',
                  'weight' => '1',
                  'volume' => '0.1',
                  'quantity' => '1'
              ]
            ];
        }

        $shipCities = City::where('is_ship', true)->with('terminal')->get();
        $selectedShipCity = null;
        $selectedDestCity = null;
        if(isset($request->ship_city)){
            $selectedShipCity = $request->ship_city;
        }else{
            $selectedShipCity = 53;
        }
        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $selectedShipCity))
            ->with('terminal')
            ->orderBy('name')
            ->get();

        if(isset($request->ship_city) && isset($request->dest_city)){
            $selectedDestCity = $request->dest_city;
        }else{
            $selectedDestCity = 78;
        }
        $route = $this->getRoute($request, $selectedShipCity,$selectedDestCity);
        $tariff = json_decode($this->getTariff($request, $packages, $selectedShipCity,$selectedDestCity)->content());

        $services = Service::get();

        return view('v1.pages.calculator.calculator-show.calculator-show')
            ->with(compact(
                'packages',
                'shipCities',
                'destinationCities',
                'route',
                'tariff',
                'services',
                'selectedShipCity',
                'selectedDestCity'
            ));

    }

    public function getDestinationCities(Request $request, $ship_city = null) {

        if($ship_city == null){
            $ship_city = $request->ship_city;
        }
        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $ship_city))
            ->with('terminal')
            ->orderBy('name')
            ->get();
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

    public function getTariff(Request $request, $packages = null, $ship_city_id = null, $dest_city_id = null, $route_id = null) {
        $formData = null;
        if(isset($request->formData)){
            $formData = array();
            parse_str($request->formData, $formData);
        }

        if($ship_city_id == null){$ship_city_id = $request->ship_city;}
        if($dest_city_id == null){$dest_city_id = $request->dest_city;}

        if($route_id == null){
            if(!isset($request->route_id)){
                $route = Route::where('ship_city_id', $ship_city_id)
                    ->where('dest_city_id', $dest_city_id)
                    ->first();
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

        $resultData = CalculatorHelper::getTariff($packages, $route_id, $services);

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

    /**
     * @param $pointName
     * @return bool|Point
     */
    function getPointByName($pointName) {
        return Point::where('name', $pointName)
                ->has('city')
                ->with('city')
                ->first() ?? false;
    }

    /**
     * @param $cityName
     * @return bool
     */
    public function getCityByName($cityName) {
        return City::where('name', $cityName)
                ->first() ?? false;
    }

    /**
     * @param Request $request:
     *
     * $request->city // название города
     * $request->weight
     * $request->volume
     * $request->units
     * $request->distance
     * $request->x2 // Умножим цену на 2, если нужна точная доставка
     *
     * @return array
     */
    public function getTariffPrice(Request $request) {
        $price = 0;

        // Изначально пытаемся получить город
        $city = $this->getCityByName($request->get('city'));

        // Если города нет, пытаемся найти пункт
        if(!$city) {
            $city = $this->getPointByName($request->get('city'));
        }

        // Если ни города, ни пункта не нашли, то выводим договорную цену
        if(!$city) {
            return [
                'price' => 'Договорная'
            ];
        }

        // Если город -- терминальный
        ////, то находим тариф за городом
        if($city instanceof Point) {
//            $outside_tariff = DB::table('outside_forwarding')
//                ->join('points', 'points.id', '=', 'outside_forwarding.point')
//                ->join('cities', 'cities.id', '=', 'points.city_id')
//                ->join('forward_thresholds', function($join)
//                {
//                    $join->on('forward_thresholds.id', '=', 'outside_forwarding.forward_threshold_id');
//                    $join->on('forward_thresholds.threshold_group_id', '=', 'cities.threshold_group_id');
//                })
//                ->where([
//                    ['points.id', $city->id],
//                    ['forward_thresholds.weight', '>=', floatval($request->get('weight'))],
//                    ['forward_thresholds.volume', '>=', floatval($request->get('volume'))],
//                ])
//                ->orderBy('forward_thresholds.weight', 'ASC')
//                ->orderBy('forward_thresholds.volume', 'ASC')
//                ->first();
//
//            // Если тариф не нашли, то выводим договорную цену
//            if(!$outside_tariff) {
//                return [
//                    'price' => 'Договорная'
//                ];
//            }
//
//            // К итоговой цене прибавляем цену фиксированного тарифа до пункта
//            $price += $outside_tariff->tariff;
            $city = $city->city; // Дальше будем работать с привязанным к пункту городом
        }

        // Найдем тариф внутри города
        $fixed_tariff = DB::table('inside_forwarding')
            ->join('forward_thresholds', function($join)
            {
                $join->on('inside_forwarding.forward_threshold_id', '=', 'forward_thresholds.id');
            })
            ->where([
                ['city_id', $city->id],
                ['forward_thresholds.weight', '>=', floatval($request->get('weight'))],
                ['forward_thresholds.volume', '>=', floatval($request->get('volume'))],
            ])
            ->orderBy('forward_thresholds.weight', 'ASC')
            ->orderBy('forward_thresholds.volume', 'ASC')
            ->first();

        $fixed_tariff = $fixed_tariff->tariff ?? false;
        if(!$fixed_tariff && $request->get('isWithinTheCity') == 'true') {
            return [
                'price' => 'Договорная'
            ];
        }

        // Если в пределах города, то возвращаем тариф согласно пределам города
        if($request->get('isWithinTheCity') == 'true') {
            return [
                'price' => $request->get('x2') === 'true' ? floatval($fixed_tariff) * 2 : floatval($fixed_tariff)
            ];
        }

        // Если за пределами города, то ищем покилометровый тариф с учетом тарифной зоны города
        $per_km_tariff = DB::table('per_km_tariffs')
            ->join('cities', 'cities.tariff_zone_id', '=', 'per_km_tariffs.tariff_zone_id')
            ->join('forward_thresholds', function($join)
            {
                $join->on('forward_thresholds.id', '=', 'per_km_tariffs.forward_threshold_id');
                $join->on('forward_thresholds.threshold_group_id', '=', 'cities.threshold_group_id');
            })
            ->where([
                ['cities.id', $city->id],
                ['forward_thresholds.weight', '>=', floatval($request->get('weight'))],
                ['forward_thresholds.volume', '>=', floatval($request->get('volume'))],
            ])
            ->orderBy('forward_thresholds.weight', 'ASC')
            ->orderBy('forward_thresholds.volume', 'ASC')
            ->first();

        if(!$per_km_tariff) {
            return [
                'price' => 'Договорная'
            ];
        }

        $per_km_tariff = floatval($per_km_tariff->tariff);

        // Стоимость доставки по городу + кол-во километров * 2 * стоимость из таблицы Тарифной зоны
        $price +=
//            $fixed_tariff +
            intval($request->get('distance')) * 2 * $per_km_tariff;
        if($request->get('x2') === 'true') { // Умножим цену на 2, если нужна точная доставка
            $price *= 2;
        }

        return [
            'distance' => intval($request->get('distance')),
            'price' => floatval($price)
        ];
    }
}
