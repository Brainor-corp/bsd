<?php

namespace App\Http\Controllers;

use App\City;
use App\ForwardThreshold;
use App\InsideForwarding;
use App\OutsideForwarding;
use App\Oversize;
use App\OversizeMarkup;
use App\PerKmTariff;
use App\Point;
use App\Region;
use App\Route;
use App\RouteTariff;
use App\Service;

use Illuminate\Http\Request;

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

        $shipCities = City::where('is_ship', true)->get();
        $selectedShipCity = null;
        $selectedDestCity = null;
        if(isset($request->ship_city)){
            $selectedShipCity = $request->ship_city;
        }else{
            $selectedShipCity = 53;
        }
        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $selectedShipCity))
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
        $query = new  OversizeMarkup;
        $query = $query->where('oversize_id',1);
        $query = $query->where(function($q) use ($package) {
            $q->orWhere([['rate_id',26],['threshold','<=',$package['weight']]]);
            $q->orWhere([['rate_id',27],['threshold','<=',$package['volume']]]);
            $q->orWhere([['rate_id',28],['threshold','<=',$package['length']]]);
            $q->orWhere([['rate_id',28],['threshold','<=',$package['width']]]);
            $q->orWhere([['rate_id',28],['threshold','<=',$package['height']]]);
        });
        $query = $query->orderBy('markup','DESC');
        $query = $query->first();

//        dd($query);
        return $query->markup / 100;
    }

    public function getTariff(Request $request, $packages = null, $ship_city_id = null, $dest_city_id = null, $route_id = null) {

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

        $weight = 0;
        $volume = 0;

        $oversizes = [];

        $oversize = Oversize::where('id', 1)->first();

        foreach ($packages as $key=>$package){

            if (!isset($package['length'])){$package['length'] = 1;}
            if (!isset($package['width'])){$package['width'] = 1;}
            if (!isset($package['height'])){$package['height'] = 0.01;}
            if (!isset($package['volume'])){$package['volume'] = 0.01;}
            if (!isset($package['weight'])){$package['weight'] = 1;}
            if (!isset($package['quantity'])){$package['quantity'] = 1;}

            if (intval($package['length']) == 0){$package['length'] = 1;}
            if (intval($package['width']) == 0){$package['width'] = 1;}
            if (intval($package['height']) == 0){$package['height'] = 0.01;}
            if (intval($package['volume']) == 0){$package['volume'] = 0.01;}
            if (intval($package['weight']) == 0){$package['weight'] = 1;}
            if (intval($package['quantity']) == 0){$package['quantity'] = 1;}

            $weight += $package['weight'] * $package['quantity'];
            $volume += $package['volume'] * $package['quantity'];


            if($package['length'] > $oversize['length']){$oversizes[$key]=$package;}
            if($package['width'] > $oversize['width']){$oversizes[$key]=$package;}
            if($package['height'] > $oversize['height']){$oversizes[$key]=$package;}
            if($package['volume'] > $oversize['volume']){$oversizes[$key]=$package;}
            if($package['weight'] > $oversize['weight']){$oversizes[$key]=$package;}
        }

        $totalVolume = $volume;

        $tariff = new \stdClass();

        $route = Route::with('oversize')->where('id', $route_id)->first();


        if ($weight <= 2 && $volume <= 0.01) {
            if ($route->wrapper_tariff > 0){
                $tariff->wrapper = $route->wrapper_tariff;
                $basePrice =  $tariff->wrapper;
                if($basePrice < $route->min_cost){
                    $basePrice =  $route->min_cost;
                }
            }
        }
        if( !isset($basePrice)) {
            if ($route->fixed_tariffs) {
                $weight = max($volume * 200, $weight);
                $volume = 0;
            }
            if ($weight) {
                $tariff->weight = RouteTariff::
                where('route_id', $route_id)
                    ->whereHas('threshold', function ($query) use ($weight) {
                        $query->where('rate_id', 26);
                        $query->where('value', '>=', $weight);
                    })
                    ->first();
                if (!$tariff->weight) {
                    $basePrice = 'договорная';
                }else{
                    $tariff->weight = $tariff->weight->price;
                }
            } else
                $tariff->weight = 0;
            if (!isset($basePrice)) {
                if ($volume) {
                    $tariff->volume = RouteTariff::
                    where('route_id', $route_id)
                        ->whereHas('threshold', function ($query) use ($volume) {
                            $query->where('rate_id', 27);
                            $query->where('value', '>=', $volume);
                        })
                        ->first();
                    if (!$tariff->volume) {
                        $basePrice = 'договорная';
                    }
                    else{
                        $tariff->volume = $tariff->volume->price;
                    }
                } else
                    $tariff->volume = 0;
                if (!isset($basePrice)) {
                    $total = 0;
                    if ($route->base_route) {
                        $route_id = $route->base_route;
                        $baseTariff = $this->getTariff($packages, $ship_city_id, $dest_city_id, $route_id);
                        $total = max($tariff->weight, $tariff->volume, $route->min_cost) + $baseTariff;
                    } else {
                        if (count($oversizes) > 0) {
                            $costs = array('min_cost' => $route->min_cost, 'weight' => $tariff->weight * $weight, 'volume' => $tariff->volume * $volume);
                            arsort($costs);
                            $key = key($costs);
                            if ($key == 'min_cost') {
                                $total = $route->min_cost;
                            } else {
                                $tariff = $tariff->$key;
                                foreach ($packages as $package) {
                                    $total += $package[$key] * ($packages['quantity'] ?? 1) * $tariff *
                                        (1 + $this->oversize_ratio($route->oversizes_id, $package) ?? 1);
                                }
                            }
                        } else {
                            $total = max($tariff->weight * ($route->fixed_tariffs ? 1 : $weight), $tariff->volume * ($route->fixed_tariffs ? 1 : $volume), $route->min_cost);
                        }
                    }
                    $basePrice = ceil($total + $route->addition);
                }
            }
        }

        $resultData = [
            'base_price'        => $basePrice,
            'total_volume'       => $totalVolume,
            'route'       => $route,
            ];
        $totalPrice = $this->getTotalPrice($request, $basePrice, $totalVolume,false);

        $resultData['total_data'] = $totalPrice;

        return response()->json($resultData);

    }

    public function getTotalPrice(Request $request, $base_price = null, $totalVolume = null, $needJson = true) {

        if(isset($request->base_price)){$base_price = $request->base_price;}
        if($base_price == null){
            $totalPrice = $request->base_price ?? 0;
        }else{
            $totalPrice = $base_price;
        }


        $result = [];

        if(isset($request->formData)){
            $formData = array();
            parse_str($request->formData, $formData);
        }

        if(isset($request->service)){$services = $request->service;}
        if(isset($formData['service'])){$services = $formData['service'];}

        if(isset($request->insurance_amount)){$insuranceAmount = $request->insurance_amount;}
        if(isset($formData['insurance_amount'])){$insuranceAmount = $formData['insurance_amount'];}

        if(isset($request->discount)){$discount = $request->discount;}
        if(isset($formData['discount'])){$discount = $formData['discount'];}

        if(isset($request->total_volume)){$totalVolume = $request->total_volume;}
        if(isset($formData['total_volume'])){$totalVolume = $formData['total_volume'];}
        if($totalVolume == null){$totalVolume = 1;}

        if(isset($services)){

            $servicesData = Service::get();

            $usedServices = [];
//            dd(is_int($totalPrice));
            foreach ($services as $serviceId){

                $currentService = $servicesData->where('id', $serviceId)->first();
                $currentServicePrice = max($currentService->price * $totalVolume, 200);

                if($totalPrice !== 'договорная'){
                    $totalPrice += $currentServicePrice;
                }else{
                    $currentServicePrice = 'договорная';
                    $totalPrice = 'договорная';
                }


                $usedServices[$serviceId]=[
                    'name'          =>          $currentService->name,
                    'slug'          =>          $currentService->slug,
                    'description'   =>          $currentService->description,
                    'price'         =>          $currentService->price,
                    'total'         =>          $currentServicePrice,
                ];
            }
            $result['services'] = $usedServices;
        }
        if(isset($insuranceAmount)){
            if(intval($insuranceAmount)>0){
                $insurancePrice = max(($insuranceAmount * 0.1000000000000000055511151231257827021181583404541015625)/100, 50);

                if($totalPrice !== 'договорная'){
                    $totalPrice += $insurancePrice;
                }else{
                    $insurancePrice = 'договорная';
                    $totalPrice = 'договорная';
                }

                $result['insurance'] = $insurancePrice;
            }
        }else{
            $insurancePrice = 50;

            $totalPrice += $insurancePrice;

            $result['insurance'] = $insurancePrice;
        }

        if(isset($discount)){

            $discountPrice = ceil(($base_price * $discount) / 100);
            $totalPrice += $discountPrice;

            $result['discount'] = $discountPrice;

        }

        $result['total'] = $totalPrice;

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
    function getPoint($pointName) {
        return Point::where('name', $pointName)
                ->with('city')
                ->first() ?? false;
    }

    /**
     * @param $cityName
     * @return bool
     */
    public function getCityByName($cityName) {
        return City::where('name', $cityName)->first() ?? false;
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
    public function getPickUpPrice(Request $request) {
        $city = $this->getCityByName($request->get('city'));
        if(!$city) {
            return abort(500, "City '{$request->get('city')}' not found"); // todo Что делать, если не нашли город?
        }

        // Найдем тариф внутри города
        $fixed_tariff = InsideForwarding::
        whereHas(
            'city',
            function ($query) use ($city) {
                $query->where('id', $city->id);
            }
        )->whereHas('forwardThreshold', function ($query) use ( $request ){
            $query->where('weight', '>=', floatval($request->get('weight')));
            $query->where('volume', '>=', floatval($request->get('volume')));
            $query->where('units', '>=', floatval($request->get('units')));
        })->first();

        $fixed_tariff = $fixed_tariff->tariff ?? false;
        $fixed_tariff = floatval($fixed_tariff);
        if(!$fixed_tariff) {
            return abort(500, 'Tariff not found'); // todo Как быть, если фикс. тариф не найден?
        }

        // Если в пределах города, то возвращаем тариф согласно пределам города
        if($request->get('isWithinTheCity') == 'true') {
            return [
                'price' => $request->get('x2') === 'true' ? $fixed_tariff * 2 : $fixed_tariff
            ];
        }

        // Если за пределами города, то ищем покилометровый тариф с учетом тарифной зоны города
        $per_km_tariff = PerKmTariff::
        whereHas(
            'forwardThreshold',
            function ($query) use ($request) {
                $query->where('weight', '>=', floatval($request->get('weight')));
                $query->where('volume', '>=', floatval($request->get('volume')));
                $query->where('units', '>=', floatval($request->get('units')));
            }
        )
            ->where('tariff_zone_id', $city->tariff_zone_id)
            ->first();

        $per_km_tariff = $per_km_tariff->tariff ?? 0;

        // Стоимость доставки по городу + кол-во километров * 2 * стоимость из таблицы Тарифной зоны
        $price = $fixed_tariff + intval($request->get('distance')) * 2 * $per_km_tariff;
        if($request->get('x2') === 'true') { // Умножим цену на 2, если нужна точная доставка
            $price *= 2;
        }

        return [
            'distance' => intval($request->get('distance')),
            'price' => $price
        ];
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
    public function getDeliveryPrice(Request $request) {
        $city = $this->getCityByName($request->get('city'));
        if(!$city) {
            return abort(500, "City '{$request->get('city')}' not found"); // todo Что делать, если не нашли город?
        }

        // Найдем тариф внутри города
        $fixed_tariff = InsideForwarding::
        whereHas(
            'city',
            function ($query) use ($city) {
                $query->where('id', $city->id);
            }
        )->whereHas('forwardThreshold', function ($query) use ( $request ){
            $query->where('weight', '>=', floatval($request->get('weight')));
            $query->where('volume', '>=', floatval($request->get('volume')));
            $query->where('units', '>=', floatval($request->get('units')));
        })->first();

        $fixed_tariff = $fixed_tariff->tariff ?? false;
        $fixed_tariff = floatval($fixed_tariff);
        if(!$fixed_tariff) {
            return abort(500, 'Tariff not found'); // todo Как быть, если фикс. тариф не найден?
        }

        // Если в пределах города, то возвращаем тариф согласно пределам города
        if($request->get('isWithinTheCity') == 'true') {
            return [
                'price' => $request->get('x2') === 'true' ? $fixed_tariff * 2 : $fixed_tariff
            ];
        }

        // Если за пределами города, то ищем покилометровый тариф с учетом тарифной зоны города
        $per_km_tariff = PerKmTariff::
        whereHas(
            'forwardThreshold',
            function ($query) use ($request) {
                $query->where('weight', '>=', floatval($request->get('weight')));
                $query->where('volume', '>=', floatval($request->get('volume')));
                $query->where('units', '>=', floatval($request->get('units')));
            }
        )
            ->where('tariff_zone_id', $city->tariff_zone_id)
            ->first();

        $per_km_tariff = $per_km_tariff->tariff ?? 0;

        // Стоимость доставки по городу + кол-во километров * 2 * стоимость из таблицы Тарифной зоны
        $price = $fixed_tariff + intval($request->get('distance')) * 2 * $per_km_tariff;
        if($request->get('x2') === 'true') { // Умножим цену на 2, если нужна точная доставка
            $price *= 2;
        }

        return [
            'distance' => intval($request->get('distance')),
            'price' => $price
        ];
    }
}
