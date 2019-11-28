<?php

namespace App\Http\Helpers;

use App\City;
use App\Oversize;
use App\OversizeMarkup;
use App\Point;
use App\Polygon;
use App\Route;
use App\RouteTariff;
use App\Service;
use Illuminate\Support\Facades\DB;

class CalculatorHelper
{
    public static function getRouteData(
        $shipCity = null,
        $destCity = null,
        $packages,
        $weight,
        $volume,
        $route_id = null
    ) {
        $route = null;

        if(isset($route_id)) {
            $route = Route::where('id', $route_id)->with('oversize')->firstOrFail();
        } elseif (isset($shipCity) && isset($destCity)) {
            $route = Route::where([
                ['ship_city_id', $shipCity->id],
                ['dest_city_id', $destCity->id]
            ])->with('oversize')->firstOrFail();
        }

        $route_id = $route->id;
        $oversizes = [];
        $oversize = Oversize::where('id', $route->oversizes_id)->first();

        foreach ($packages as $key => $package) {
            if (!isset($package['length'])) {
                $package['length'] = 1;
            }
            if (!isset($package['width'])) {
                $package['width'] = 1;
            }
            if (!isset($package['height'])) {
                $package['height'] = 0.01;
            }
            if (!isset($package['volume'])) {
                $package['volume'] = 0.01;
            }
            if (!isset($package['weight'])) {
                $package['weight'] = 1;
            }
            if (!isset($package['quantity'])) {
                $package['quantity'] = 1;
            }

            if (intval($package['length']) == 0) {
                $package['length'] = 1;
            }
            if (intval($package['width']) == 0) {
                $package['width'] = 1;
            }
            if (intval($package['height']) == 0) {
                $package['height'] = 0.01;
            }
            if (intval($package['volume']) == 0) {
                $package['volume'] = 0.01;
            }
            if (intval($package['weight']) == 0) {
                $package['weight'] = 1;
            }
            if (intval($package['quantity']) == 0) {
                $package['quantity'] = 1;
            }

//            $weight += $package['weight'] * $package['quantity'];
//            $volume += $package['volume'] * $package['quantity'];


            if ($package['length'] > $oversize['length']) {
                $oversizes[$key] = $package;
            }
            if ($package['width'] > $oversize['width']) {
                $oversizes[$key] = $package;
            }
            if ($package['height'] > $oversize['height']) {
                $oversizes[$key] = $package;
            }
            if ($package['volume'] > $oversize['volume']) {
                $oversizes[$key] = $package;
            }
            if ($package['weight'] > $oversize['weight']) {
                $oversizes[$key] = $package;
            }
        }

        $totalVolume = $volume;
        $totalWeight = $weight;

        $tariff = new \stdClass();

        if ($weight <= 2 && $volume <= 0.01) {
            if ($route->wrapper_tariff > 0) {
                $tariff->wrapper = $route->wrapper_tariff;
                $basePrice = $tariff->wrapper;
            }
        }
        if (!isset($basePrice)) {
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
                } else {
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
                    } else {
                        $tariff->volume = $tariff->volume->price;
                    }
                } else
                    $tariff->volume = 0;
                if (!isset($basePrice)) {
                    $total = 0;
                    if ($route->base_route) {
                        $route_id = $route->base_route;
                        $baseTariff = self::getRouteData(null, null, $packages, $totalWeight, $totalVolume, $route_id);
                        if(is_numeric($baseTariff['price'])) {
                            $total = max($tariff->weight, $tariff->volume, $route->min_cost) + $baseTariff['price'];
                        } else {
                            $total = "договорная";
                        }
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
                                    $oversizeRation = self::oversize_ratio($route->oversizes_id, $package);
                                    if($oversizeRation === false) {
                                        $total = "договорная";
                                        break;
                                    }

                                    $total += $package[$key] * ($packages['quantity'] ?? 1) * $tariff *
                                        ($oversizeRation ?? 1);
                                }
                            }
                        } else {
                            $total = max($tariff->weight * ($route->fixed_tariffs ? 1 : $weight), $tariff->volume * ($route->fixed_tariffs ? 1 : $volume), $route->min_cost);
                        }
                    }
                    if(is_numeric($total)) {
                        $basePrice = ceil($total + $route->addition);
                    } else {
                        $basePrice = "договорная";
                    }
                }
            }
        }

        return [
            'model' => $route,
            'totalVolume' => $totalVolume,
            'totalWeight' => $totalWeight,
            'price' => $basePrice
        ];
    }

    public static function getServicesData(
        $services,
        $insuranceAmount,
        $totalWeight,
        $totalVolume,
        $insuranceNeed = true
    ) {
        $servicesData = Service::get();

        $usedServices = [];
        if(isset($services)) {
            foreach ($services as $serviceId){

                $currentService = $servicesData->where('id', $serviceId)->first();
                $currentServicePrice = max($currentService->price * $totalVolume, 200);

                $usedServices[$serviceId] = [
                    'id'            =>          $currentService->id,
                    'name'          =>          $currentService->name,
                    'slug'          =>          $currentService->slug,
                    'description'   =>          $currentService->description,
                    'price'         =>          round($currentService->price, 2),
                    'total'         =>          round($currentServicePrice, 2),
                ];
            }
        }
        if($insuranceNeed) {
            $insurancePrice = 50;
            if(isset($insuranceAmount)){
                if(intval($insuranceAmount)>0){
                    $insurancePrice = max(($insuranceAmount * 0.1000000000000000055511151231257827021181583404541015625)/100, 50);
                }
            }
            $usedServices['insurance'] = [
                'name'          => 'Страховка',
                'slug'          => '',
                'description'   => '',
                'price'         => round($insurancePrice, 2),
                'total'         => round($insurancePrice, 2),
            ];
        }
        return $usedServices;
    }

    public static function oversize_ratio($oversizes_id, $package)
    {
        $query = new  OversizeMarkup();
        $query = $query->where('oversize_id', $oversizes_id);
        $query = $query->where(function ($q) use ($package) {
            $q->orWhere([['rate_id', 26], ['threshold', '<=', $package['weight']]]);
            $q->orWhere([['rate_id', 27], ['threshold', '<=', $package['volume']]]);

            if(isset($package['length'])) {
                $q->orWhere([['rate_id', 28], ['threshold', '<=', $package['length']]]);
            }

            if(isset($package['width'])) {
                $q->orWhere([['rate_id', 28], ['threshold', '<=', $package['width']]]);
            }

            if(isset($package['height'])) {
                $q->orWhere([['rate_id', 28], ['threshold', '<=', $package['height']]]);
            }
        });
        $query = $query->orderBy('markup', 'DESC');
        $query = $query->first();

//        dd($query);
        return isset($query) ? $query->markup / 100 : false;
    }

    public static function getTotalPrice($base_price, $services, $totalWeight, $totalVolume, $insuranceAmount = null, $discount = null, $take_price = null, $bring_price = null) {
        $result = [];

        $totalPrice = $base_price;

        // Возьмём в учёт цену за забор и доставку груза
        if(is_numeric($totalPrice)) {
            if(isset($take_price)) {
                if(is_numeric($take_price)) {
                    $totalPrice += floatval($take_price);
                } else {
                    $totalPrice = 'договорная';
                }
            }
            if(isset($bring_price)) {
                if(is_numeric($bring_price)) {
                    $totalPrice += floatval($bring_price);
                } else {
                    $totalPrice = 'договорная';
                }
            }
        }

        if(isset($services)){

            $servicesData = Service::get();

            $usedServices = [];
//            dd(is_int($totalPrice));
            foreach ($services as $serviceId){

                $currentService = $servicesData->where('id', $serviceId)->first();
                $currentServicePrice = max($currentService->price * $totalVolume, 200);

                if(is_numeric($totalPrice)){
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
            if ($totalWeight <= 2 && $totalVolume <= 0.01) {}
            else {
                $insurancePrice = 50;

                if (is_numeric($totalPrice) && is_numeric($insurancePrice)) {
                    $totalPrice += $insurancePrice;
                }

                $result['insurance'] = $insurancePrice;
            }
        }

        if(isset($discount)){
            if(is_numeric($base_price) && is_numeric($discount)) {
                $discountPrice = ceil(($base_price * $discount) / 100);
            } else {
                $discountPrice = "договорная";
            }
            if(is_numeric($totalPrice) && is_numeric($discountPrice)) {
                $totalPrice -= $discountPrice;
            } else {
                $totalPrice = "договорная";
            }

            $result['discount'] = $discountPrice;

        }

        $result['total'] = $totalPrice;

        return $result;
    }

    public static function getTariffPrice(
        $baseCityName,
        $cityName,
        $weight,
        $volume,
        $isWithinTheCity,
        $x2,
        $packages,
        $distance = null,
        $polygonId = null,
        $displayCityName = null
    ) {
        $price = 0;
        $displayCityName = $displayCityName ?? $cityName;

        // Изначально пытаемся найти особый населённый пункт
        $city = self::getPointByName($cityName);

        // Если пункта нет, пытаемся получить город
        if(!$city) {
            $city = self::getCityByName($cityName);
        }

        // Если ни города, ни пункта не нашли
        if(!$city) {
            // Попробуем поиск по названию города отправления/назначения.
            // При это доставку в таком случае будем всегда считать как за пределами города.
            if($baseCityName !== $cityName) {
                return self::getTariffPrice(
                    $baseCityName,
                    $baseCityName,
                    $weight,
                    $volume,
                    false,
                    $x2,
                    $packages,
                    $distance,
                    $polygonId,
                    $displayCityName
                );
            } else { // Если не нашли и по названию города отправления/назначения, выводим договорную цену
                return $isWithinTheCity ? [
                    'price' => 'договорная',
                    'city_name' => $displayCityName
                ] : [
                    'price' => 'договорная',
                    'city_name' => $displayCityName,
                    'distance' => intval($distance),
                ];
            }
        }

        $packagesCount = count($packages);
        if($packagesCount == 1){$packagesCount = 0;}

        // Если город -- особый пункт
        $point_fixed_tariff = false;
        if($city instanceof Point) {
            $point_fixed_tariff = DB::table('outside_forwardings')
                ->join('forward_thresholds', function($join)
                {
                    $join->on('outside_forwardings.forward_threshold_id', '=', 'forward_thresholds.id');
                })
                ->where([
                    ['point', $city->id],
                    ['forward_thresholds.weight', '>=', floatval($weight)],
                    ['forward_thresholds.volume', '>=', floatval($volume)],
                    ['forward_thresholds.units', '>=', $packagesCount],
                ])
                ->orderBy('forward_thresholds.weight', 'ASC')
                ->orderBy('forward_thresholds.volume', 'ASC')
                ->orderBy('forward_thresholds.units', 'ASC')
                ->first();

            $point_fixed_tariff = $point_fixed_tariff->tariff ?? false;

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
                ['forward_thresholds.weight', '>=', floatval($weight)],
                ['forward_thresholds.volume', '>=', floatval($volume)],
                ['forward_thresholds.units', '>=', $packagesCount],
            ])
            ->orderBy('forward_thresholds.weight', 'ASC')
            ->orderBy('forward_thresholds.volume', 'ASC')
            ->orderBy('forward_thresholds.units', 'ASC')
            ->first();

        $fixed_tariff = $fixed_tariff->tariff ?? false;
        if(!$fixed_tariff && $isWithinTheCity) {
            return [
                'price' => 'договорная',
                'city_name' => $displayCityName,
                'distance' => intval($distance),
            ];
        }

        // Если в пределах города, то возвращаем тариф согласно пределам города
        if($isWithinTheCity) {
            // Если есть полигон, то возвращаем его цену
            if(!empty($polygonId)) {
                $polygon = Polygon::where([
                    ['id', $polygonId],
                    ['city_id', $city->id]
                ])->first();

                if(isset($polygon)) {
                    return [
                        'price' => $x2 ? ($polygon->price * floatval($fixed_tariff)) * 2 : ($polygon->price * floatval($fixed_tariff)),
                        'city_name' => "$displayCityName",
                        'polygon_name' => $polygon->name
                    ];
                }
            }

            return [
                'price' => $x2 ? floatval($fixed_tariff) * 2 : floatval($fixed_tariff),
                'city_name' => $displayCityName
            ];
        }

        // Если есть полигон, то возвращаем его цену
        if(!empty($polygonId)) {
            $polygon = Polygon::where([
                ['id', $polygonId],
                ['city_id', $city->id]
            ])->first();

            if(isset($polygon)) {
                return [
                    'price' => $x2 ? ($polygon->price * floatval($fixed_tariff)) * 2 : ($polygon->price * floatval($fixed_tariff)),
                    'city_name' => "$displayCityName",
                    'polygon_name' => $polygon->name
                ];
            }
        }

        if($point_fixed_tariff) {
            return [
                'price' => $x2 ? $point_fixed_tariff * 2 : $point_fixed_tariff,
                'city_name' => $displayCityName,
                'distance' => intval($distance),
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
                ['forward_thresholds.weight', '>=', floatval($weight)],
                ['forward_thresholds.volume', '>=', floatval($volume)],
                ['forward_thresholds.units', '>=', count($packages)],
            ])
            ->orderBy('forward_thresholds.weight', 'ASC')
            ->orderBy('forward_thresholds.volume', 'ASC')
            ->orderBy('forward_thresholds.units', 'ASC')
            ->first();

        if(!$per_km_tariff) {
            return [
                'price' => 'договорная',
                'city_name' => $displayCityName,
                'distance' => intval($distance),
            ];
        }

        $per_km_tariff = floatval($per_km_tariff->tariff);

        // Стоимость доставки по городу + кол-во километров * 2 * стоимость из таблицы Тарифной зоны
        $price += $fixed_tariff + intval($distance) * 2 * $per_km_tariff;
        if($x2) { // Умножим цену на 2, если нужна точная доставка
            $price *= 2;
        }

        return [
            'city_name' => $displayCityName,
            'distance' => intval($distance),
            'price' => floatval($price)
        ];
    }

    /**
     * @param $pointName
     * @return bool|Point
     */
    public static function getPointByName($pointName) {
        return Point::where('name', $pointName)
                ->has('city')
                ->with('city')
                ->first() ?? false;
    }

    /**
     * @param $cityName
     * @return bool
     */
    public static function getCityByName($cityName) {
        return City::where('name', $cityName)
                ->first() ?? false;
    }

    /**
     * Возвращает все просчитанные значения для основного калькулятора
     *
     * @param City $shipCity
     * @param City $destCity
     * @param array $packages
     * @param $total_weight
     * @param $total_volume
     * @param array $services
     * @param array $takeParams
     * @param array $bringParams
     * @param $insuranceAmount
     * @param $discount
     * @param bool $insuranceNeed
     * @return array
     */
    public static function getAllCalculatedData(
        City $shipCity,
        City $destCity,
        $packages = [],
        $total_weight,
        $total_volume,
        $services = [],
        Array $takeParams = [],
        Array $bringParams = [],
        $insuranceAmount,
        $discount,
        $insuranceNeed = true
    ) {
        $totalPrice = "договорная";
        $servicesPrice = 0;

        $routeData = self::getRouteData($shipCity, $destCity, $packages, $total_weight, $total_volume);

        $servicesData = self::getServicesData(
            $services,
            $insuranceAmount,
            $total_weight,
            $total_volume,
            $insuranceNeed
        );
        foreach($servicesData as $service) {
            if(is_numeric($service['total'])) {
                $servicesPrice += floatval($service['total']);
            } else {
                $servicesPrice = "договорная";
                break;
            }
        }

        $takeData = null;
        if(!empty($takeParams)) {
            $takeData = self::getTariffPrice(
                $takeParams['baseCityName'],
                $takeParams['cityName'],
                $takeParams['weight'],
                $takeParams['volume'],
                $takeParams['isWithinTheCity'],
                $takeParams['x2'],
                $packages,
                $takeParams['distance'],
                $takeParams['polygonId']
            );
        }

        $bringData = null;
        if(!empty($bringParams)) {
            $bringData = self::getTariffPrice(
                $bringParams['baseCityName'],
                $bringParams['cityName'],
                $bringParams['weight'],
                $bringParams['volume'],
                $bringParams['isWithinTheCity'],
                $bringParams['x2'],
                $packages,
                $bringParams['distance'],
                $bringParams['polygonId']
            );
        }

        if(
            is_numeric($routeData['price']) &&
            is_numeric($servicesPrice) &&
            ($takeData == null || is_numeric($takeData['price'])) &&
            ($bringData == null || is_numeric($bringData['price']))
        ) {
            $totalPrice = floatval($routeData['price']) +
                floatval($servicesPrice) +
                floatval($takeData['price'] ?? 0) +
                floatval($bringData['price'] ?? 0);
        }

        if(is_numeric($totalPrice)) {
            $discount = round($totalPrice * ($discount / 100), 2);
            $totalPrice -= $discount;
        } else {
            $discount = "договорная";
        }

        return [
            'route' => [ // Базовый маршрут доставки
                'name' => $routeData['model']->name, // Название маршрута
                'price' => $routeData['price'] // Цена доставки
            ],
            'delivery' => [ // Забор/доставка груза
                'take' => $takeData, // Забор
                'bring' => $bringData, // Доставка
            ],
            'services' => $servicesData, // Список услуг
            'discount' => $discount, // Размер скидки
            'total' => $totalPrice, // Общая цена за доставку
        ];
    }
}
