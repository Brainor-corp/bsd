<?php


namespace App\Http\Helpers;

use App\Oversize;
use App\OversizeMarkup;
use App\Route;
use App\RouteTariff;
use App\Service;

class CalculatorHelper
{

    public static function oversize_ratio($package)
    {
        $query = new  OversizeMarkup();
        $query = $query->where('oversize_id', 1);
        $query = $query->where(function ($q) use ($package) {
            $q->orWhere([['rate_id', 26], ['threshold', '<=', $package['weight']]]);
            $q->orWhere([['rate_id', 27], ['threshold', '<=', $package['volume']]]);
            $q->orWhere([['rate_id', 28], ['threshold', '<=', $package['length']]]);
            $q->orWhere([['rate_id', 28], ['threshold', '<=', $package['width']]]);
            $q->orWhere([['rate_id', 28], ['threshold', '<=', $package['height']]]);
        });
        $query = $query->orderBy('markup', 'DESC');
        $query = $query->first();

//        dd($query);
        return $query->markup / 100;
    }

    public static function getTotalPrice($base_price, $services, $totalVolume, $insuranceAmount = null, $discount = null, $take_price = null, $bring_price = null) {
        $result = [];

        $totalPrice = $base_price;

        // Возьмём в учёт цену за забор и доставку груза
        if(is_numeric($totalPrice)) {
            if(isset($take_price)) {$totalPrice += floatval($take_price);}
            if(isset($bring_price)) {$totalPrice += floatval($bring_price);}
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
            $insurancePrice = 50;

            if(is_numeric($totalPrice) && is_numeric($insurancePrice)) {
                $totalPrice += $insurancePrice;
            }

            $result['insurance'] = $insurancePrice;
        }

        if(isset($discount)){
            if(is_numeric($base_price) && is_numeric($discount)) {
                $discountPrice = ceil(($base_price * $discount) / 100);
            } else {
                $discountPrice = "Договорная";
            }
            if(is_numeric($totalPrice) && is_numeric($discountPrice)) {
                $totalPrice -= $discountPrice;
            } else {
                $totalPrice = "Договорная";
            }

            $result['discount'] = $discountPrice;

        }

        $result['total'] = $totalPrice;

        return $result;
    }

    public static function getTariff($packages, $route_id, $services = null, $insuranceAmount = null, $discount = null, $totalNeed = true)
    {
        $weight = 0;
        $volume = 0;

        $oversizes = [];

        $oversize = Oversize::where('id', 1)->first();

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

            $weight += $package['weight'] * $package['quantity'];
            $volume += $package['volume'] * $package['quantity'];


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

        $tariff = new \stdClass();

        $route = Route::with('oversize')->where('id', $route_id)->first();

        if ($weight <= 2 && $volume <= 0.01) {
            if ($route->wrapper_tariff > 0) {
                $tariff->wrapper = $route->wrapper_tariff;
                $basePrice = $tariff->wrapper;
                if ($basePrice < $route->min_cost) {
                    $basePrice = $route->min_cost;
                }
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
                        $baseTariff = self::getTariff($packages, $route_id);
                        if(is_numeric($baseTariff)) {
                            $total = max($tariff->weight, $tariff->volume, $route->min_cost) + $baseTariff;
                        } else {
                            $total = "Договорная";
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
                                    $total += $package[$key] * ($packages['quantity'] ?? 1) * $tariff *
                                        (1 + self::oversize_ratio($route->oversizes_id, $package) ?? 1);
                                }
                            }
                        } else {
                            $total = max($tariff->weight * ($route->fixed_tariffs ? 1 : $weight), $tariff->volume * ($route->fixed_tariffs ? 1 : $volume), $route->min_cost);
                        }
                    }
                    if(is_numeric($total)) {
                        $basePrice = ceil($total + $route->addition);
                    } else {
                        $basePrice = "Договорная";
                    }
                }
            }
        }

        $resultData = [
            'base_price' => $basePrice,
            'total_volume' => $totalVolume,
            'route' => $route,
        ];

        if ($totalNeed) {
            $totalPrice = self::getTotalPrice($basePrice, $services, $totalVolume, $insuranceAmount, $discount);
            $resultData['total_data'] = $totalPrice;
        }

        return $resultData;
    }
}