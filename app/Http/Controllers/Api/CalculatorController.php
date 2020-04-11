<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\Http\Controllers\Controller;
use App\Route;
use App\RouteTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CalculatorController extends Controller
{
    // 0 - По весу; 1 - По объёму; 2 - Минимальная цена; 3 - Бандероль.
    private static function getKindByField($field)
    {
        $result = null;

        switch ($field) {
            case "weight":          $result = 0; break;
            case "volume":          $result = 1; break;
            case "min_cost":        $result = 2; break;
            case "wrapper_tariff":  $result = 3; break;
        }

        return $result;
    }

    private static function getRouteTariffData($route, $volume, $weight) {
        $route_id = $route->id;
        $totalVolume = $volume;
        $totalWeight = $weight;

        $kind = null; // 0 - По весу; 1 - По объёму; 2 - Минимальная цена; 3 - Бандероль.

        $tariff = new \stdClass();

        if($weight <= 2 && $volume <= 0.01) {
            if($route->wrapper_tariff > 0) {
                $tariff->wrapper = $route->wrapper_tariff;
                $basePrice = $tariff->wrapper;

                $kind = self::getKindByField('wrapper_tariff'); // Бандероль.
            }
        }
        if(!isset($basePrice)) {
            if($route->fixed_tariffs) {
                $weight = max($volume * 200, $weight);
                $volume = 0;
            }
            if($weight) {
                $tariff->weight = RouteTariff::where('route_id', $route_id)
                    ->whereHas('threshold', function ($query) use ($weight) {
                        $query->where('rate_id', 26);
                        $query->where('value', '>=', $weight);
                    })
                    ->first();
                if(!$tariff->weight) {
                    $basePrice = 'договорная';
                } else {
                    $tariff->weight = $tariff->weight->price;
                }
            } else
                $tariff->weight = 0;
            if(!isset($basePrice)) {
                if($volume) {
                    $tariff->volume = RouteTariff::
                    where('route_id', $route_id)
                        ->whereHas('threshold', function ($query) use ($volume) {
                            $query->where('rate_id', 27);
                            $query->where('value', '>=', $volume);
                        })
                        ->first();
                    if(!$tariff->volume) {
                        $basePrice = 'договорная';
                    } else {
                        $tariff->volume = $tariff->volume->price;
                    }
                } else
                    $tariff->volume = 0;
                if(!isset($basePrice)) {
                    if($route->base_route) {
                        $baseRoute = Route::find($route->base_route);
                        $baseTariff = self::getRouteTariffData($baseRoute, $totalVolume, $totalWeight);
                        if(is_numeric($baseTariff['tariff'])) {
                            $costs = array(
                                'min_cost' => $route->min_cost,
                                'weight' => $tariff->weight,
                                'volume' => $tariff->volume
                            );

                            arsort($costs);
                            $maxField = key($costs);
                            $kind = self::getKindByField($maxField);

                            $total = $costs[$maxField] + $baseTariff['tariff'];
                        } else {
                            $total = "договорная";
                        }
                    } else {
                        $costs = array(
                            'min_cost' => $route->min_cost,
                            'weight' => $tariff->weight * ($route->fixed_tariffs ? 1 : $weight),
                            'volume' => $tariff->volume * ($route->fixed_tariffs ? 1 : $volume)
                        );
                        arsort($costs);
                        $maxField = key($costs);
                        $kind = self::getKindByField($maxField);

                        $total = $costs[$maxField];
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
            "status" => "success",
            "tariff" => $basePrice,
            "minCost" => floatval($route->min_cost),
            "kind" => $kind
        ];
    }

    public function getRouteTariff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route' => 'required|integer',
            'volume' => 'required|numeric',
            'weight' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response(
                json_encode([
                    "status" => "error",
                    "text" => $validator->errors()->first()
                ], JSON_UNESCAPED_UNICODE),
                400,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']
            );
        }

        $route = Route::where('old_id', $request->get('route'))
            ->with('oversize')
            ->first();

        $volume = $request->get('volume');
        $weight = $request->get('weight');

        if(!isset($route)) {
            return response(
                json_encode(["status" => "not found"], JSON_UNESCAPED_UNICODE),
                404,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']
            );
        }

        $routeTariffData = self::getRouteTariffData($route, $volume, $weight);

        if(!isset($routeTariffData['tariff']) || !is_numeric($routeTariffData['tariff'])) {
            return response(
                json_encode(["status" => "not found"], JSON_UNESCAPED_UNICODE),
                404,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']
            );
        }

        return response(
            json_encode($routeTariffData, JSON_UNESCAPED_UNICODE),
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']
        );
    }

    public function getInsideForwardingTariff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'point' => 'required|integer',
            'volume' => 'required|numeric',
            'weight' => 'required|numeric',
            'units' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response(
                json_encode([
                    "status" => "error",
                    "text" => $validator->errors()->first()
                ], JSON_UNESCAPED_UNICODE),
                400,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']
            );
        }

        $city = City::where('kladr_id', $request->get('point'))->first();

        $volume = $request->get('volume');
        $weight = $request->get('weight');
        $packagesCount = $request->get('units');

        if(!isset($city)) {
            return response(
                json_encode([
                    "status" => "not found"
                ], JSON_UNESCAPED_UNICODE),
                404,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']
            );
        }

        $fixed_tariff = DB::table('inside_forwarding')
            ->join('forward_thresholds', function($join)
            {
                $join->on('inside_forwarding.forward_threshold_id', '=', 'forward_thresholds.id');
            })
            ->where([
                ['city_id', $city->id],
                ['forward_thresholds.weight', '>=', floatval($weight)],
                ['forward_thresholds.volume', '>=', floatval($volume)],
                ['forward_thresholds.units', '>=', intval($packagesCount)],
            ])
            ->orderBy('forward_thresholds.weight', 'ASC')
            ->orderBy('forward_thresholds.volume', 'ASC')
            ->orderBy('forward_thresholds.units', 'ASC')
            ->first();

        if(!isset($fixed_tariff)) {
            return response(
                json_encode([
                    "status" => "not found"
                ], JSON_UNESCAPED_UNICODE),
                404,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']
            );
        }

        return response(
            json_encode([
                'status' => 'success',
                'tariff' => floatval($fixed_tariff->tariff)
            ], JSON_UNESCAPED_UNICODE),
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']
        );
    }
}
