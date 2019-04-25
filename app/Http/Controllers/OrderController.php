<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Helpers\CalculatorHelper;
use App\Http\Helpers\EventHelper;
use App\Http\Helpers\YandexHelper;
use App\Order;
use App\OrderItem;
use App\Route;
use App\Service;
use App\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orderSave(Request $request) {
        $totalWeight = $totalVolume = 0;
        $packages = [];
        foreach($request->get('cargo')['packages'] as $package) {
            $packages[] = new OrderItem([
                'length' => $package['length'],
                'width' => $package['width'],
                'height' => $package['height'],
                'volume' => $package['length'] * $package['width'] * $package['height'],
                'weight' => $package['weight'],
                'quantity' => $package['quantity'],
            ]);

            $totalWeight += $package['weight'] * $package['quantity'];
            $totalVolume += $package['volume'] * $package['quantity'];
        }

        if(!count($packages)) {
            return abort(500, 'Не указаны габариты.');
        }

        $allTypes = Type::where('class', 'payer_type')
            ->orWhere('class', 'payment_type')
            ->orWhere(function ($q) {
                return $q->where('class', 'order_status')
                    ->whereIn('slug', ['chernovik', 'ozhidaet-moderacii']);
            })
            ->get();

        $cities = City::whereIn('id', [
            $request->get('ship_city'),
            $request->get('dest_city'),
        ])->with('terminal')->get();

        if($cities->count() != 2) {
            return abort(500, 'Город(а) маршрута не найдены.');
        }

        $shipCity = $cities->where('id', $request->get('ship_city'))->first();
        $destCity = $cities->where('id', $request->get('dest_city'))->first();

        $route = Route::where([
            ['ship_city_id', $shipCity->id],
            ['dest_city_id', $destCity->id],
        ])->firstOrFail();

        $payerType = $allTypes->where('class', 'payer_type')
                ->where('slug', $request->get('payer_type'))
                ->first() ?? false;

        if(!$payerType) {
            return abort(500, 'Тип плательщика не найден.');
        }

        $orderStatus = $allTypes->where('class', 'order_status')
                ->where('slug', $request->get('status'))
                ->first() ?? false;

        if(!$orderStatus) {
            return abort(500, 'Статус заказа не найден.');
        }

        $paymentType = $allTypes->where('class', 'payment_type')
                ->where('slug', $request->get('payment'))
                ->first() ?? false;

        if(!$paymentType) {
            return abort(500, 'Тип оплаты не найден.');
        }

        $tariff = CalculatorHelper::getTariff(
            $request->get('cargo')['packages'],
            $route->id,
            $request->get('service'),
            $request->get('insurance_amount'),
            $request->get('discount'),
            true
        );

        if(!isset($tariff) || !isset($tariff['base_price']) || !isset($tariff['total_data']['total'])) {
            return abort(500, 'Не удалось определить тариф');
        }

        $allServices = Service::all('id', 'slug');

        $services = [];
        if(isset($tariff['total_data']['services'])) {
            foreach($tariff['total_data']['services'] as $service) {
                if(in_array($service['slug'], $allServices->pluck('slug')->toArray())) {
                    $services[$allServices->where('slug', $service['slug'])->first()->id] = [
                        'price' => $service['total']
                    ];
                }
            }
        }

        $order = new Order;

        // Если нужен забор груза
        if($request->get('need-to-take') === "on") {
            $order->take_point = $request->get('ship-from-point') === "on";
            $order->take_in_city = $request->get('need-to-take-type') === "in";

            // Если забор груза за пределами города
            if($request->get('need-to-take-type') === "from") {
                $pointFrom = $shipCity->terminal->geo_point ?? YandexHelper::getCoordinates($shipCity->name);
                $pointTo = YandexHelper::getCoordinates($request->get('ship_point'));
                $takeDistance = YandexHelper::getDistance($pointFrom, $pointTo);

                if(!$takeDistance) {
                    return abort(500, 'Не удалось определить дистанцию для забора груза');
                }

                $takePrice = CalculatorHelper::getTariffPrice(
                    $shipCity->name,
                    $totalWeight,
                    $totalVolume,
                    $request->get('need-to-take-type') === "in",
                    $request->get('ship-from-point') === "on",
                    $takeDistance
                );

                if(!isset($takePrice['price'])) {
                    return abort(500, 'Не удалось определить цену для забора груза');
                }
                $takePrice = $takePrice['price'];

                $take_city_name = strpos( // Проверяем, чтобы название города содержалось в адресе
                    $request->get('ship_point'),
                    $request->get('take_city_name')
                ) ? $request->get('take_city_name') : null;

                $order->take_address = $request->get('ship_point'); // Адрес забора
                $order->take_city_name = $take_city_name; // Город забора
                $order->take_distance = $takeDistance; // Дистанция от города отправки до адреса забора
                $order->take_price = $takePrice; // Цена забора
            }
        }

        // Если нужна доставка груза
        if($request->get('need-to-bring') === "on") {
            $order->delivery_point = $request->get('bring-to-point') === "on";
            $order->delivery_in_city = $request->get('need-to-bring-type') === "in";

            // Если доставка за пределами города
            if($request->get('need-to-bring-type') === "from") {
                $pointFrom = ($destCity->terminal->geo_point ?? $destCity->terminal->address) ?? YandexHelper::getCoordinates($destCity->name);
                $pointTo = YandexHelper::getCoordinates($request->get('dest_point'));
                $bringDistance = YandexHelper::getDistance($pointFrom, $pointTo);

                if(!$bringDistance) {
                    return abort(500, 'Не удалось определить дистанцию для забора груза');
                }

                $bringPrice = CalculatorHelper::getTariffPrice(
                    $destCity->name,
                    $totalWeight,
                    $totalVolume,
                    $request->get('need-to-bring-type') === "in",
                    $request->get('bring-to-point') === "on",
                    $bringDistance
                );

                if(!isset($bringPrice['price'])) {
                    return abort(500, 'Не удалось определить цену для забора груза');
                }
                $bringPrice = $bringPrice['price'];

                $delivery_city_name = strpos( // Проверяем, чтобы название города содержалось в адресе
                    $request->get('dest_point'),
                    $request->get('bring_city_name')
                ) ? $request->get('bring_city_name') : null;

                $order->delivery_address = $request->get('dest_point'); // Адрес доставки
                $order->delivery_city_name = $delivery_city_name; // Город доставки
                $order->delivery_distance = $bringDistance; // Дистанция от города назначения до адреса доставки
                $order->delivery_price = $bringPrice; // Цена доставки
            }
        }

        $order->total_price = $tariff['total_data']['total'];
        $order->base_price = $tariff['base_price'];
        $order->shipping_name = $request->get('cargo')['name'];
        $order->total_weight = $totalWeight;
        $order->ship_city_id = $shipCity->id;
        $order->ship_city_name = $shipCity->name;
        $order->dest_city_id = $destCity->id;
        $order->dest_city_name = $destCity->name;
        $order->take_need = $request->get('need-to-take') === "on"; // Нужен ли забор груза
        $order->delivery_need = $request->get('need-to-bring') === "on"; // Нужна ли доставка груза
        $order->sender_name = $request->get('sender_name');
        $order->sender_phone = $request->get('sender_phone');
        $order->recepient_name = $request->get('recepient_name');
        $order->recepient_phone = $request->get('recepient_phone');
        $order->discount = $request->get('discount');
        $order->discount_amount = $tariff['total_data']['discount'] ?? null;
        $order->insurance = $request->get('insurance_amount');
        $order->insurance_amount = $tariff['total_data']['insurance'] ?? null;
        $order->user_id = Auth::user()->id ?? null;
        $order->enter_id = $_COOKIE['enter_id'];
        $order->payer_type = $payerType->id;
        $order->payer_name = $request->get('payer_name');
        $order->payer_phone = $request->get('payer_phone');
        $order->payment_type = $paymentType->id;
        $order->status_id = $orderStatus->id;
        $order->order_date = Carbon::now();

        $order->save();

        $order->order_items()->saveMany($packages);
        $order->order_services()->attach($services);

        if($orderStatus->slug === "ozhidaet-moderacii" && Auth::check()) {
            EventHelper::createEvent(
                'Заказ успешно зарегистрирован!',
                null,
                true,
                route('report-show', ['id' => $order->id], $absolute = false)
            );
        }

        return Auth::check() ? redirect(route('report-show', ['id' => $order->id])) : redirect()->back();
    }
}
