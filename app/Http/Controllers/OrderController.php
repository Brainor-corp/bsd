<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Helpers\CalculatorHelper;
use App\Http\Helpers\EventHelper;
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
        $totalWidth = 0;

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

            $totalWidth += $package['width'] * $package['quantity'];
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
        ])->get();

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
        $order->total_price = $tariff['total_data']['total'];
        $order->base_price = $tariff['base_price'];
        $order->shipping_name = $request->get('cargo')['name'];
        $order->total_weight = $totalWidth;
        $order->ship_city_id = $shipCity->id;
        $order->ship_city_name = $shipCity->name;
        $order->dest_city_id = $destCity->id;
        $order->dest_city_name = $destCity->name;
        $order->take_need = $request->get('need-to-take') === "on";
        $order->take_address = $request->get('ship_point');
        $order->take_in_city = $request->get('need-to-take-type') === "in";
        $order->take_point = $request->get('ship-from-point') === "on";
        $order->take_distance = 0; // todo
        $order->delivery_need = $request->get('need-to-bring') === "on";
        $order->delivery_address = $request->get('dest_point');
        $order->delivery_in_city = $request->get('need-to-bring-type') === "in";
        $order->delivery_point = $request->get('bring-to-point') === "on";
        $order->delivery_distance = 0; // todo
        $order->sender_name = $request->get('sender_name');
        $order->sender_phone = $request->get('sender_phone');
        $order->recepient_name = $request->get('recepient_name');
        $order->recepient_phone = $request->get('recepient_phone');
        $order->discount = $tariff['total_data']['discount'] ?? null;
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
