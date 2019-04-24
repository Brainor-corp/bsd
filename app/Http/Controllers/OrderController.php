<?php

namespace App\Http\Controllers;

use App\City;
use App\Order;
use App\OrderItem;
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
                'quantity' => $package['quantity'],
            ]);

            $totalWidth += $package['width'] * $package['quantity'];
        }

        if(!count($packages)) {
            return abort(404);
        }

        $allTypes = Type::where('class', 'payer_type')
            ->orWhere('class', 'payment_type')
            ->orWhere(function ($q) {
                return $q->where('class', 'order_status')
                    ->whereIn('slug', ['chernovik', 'ozhidaet-moderacii']);
            })
            ->get();

        $allServices = Service::all('id')->pluck('id')->toArray();

        $services = [];
        foreach($request->get('service') as $service) {
            if(in_array($service, $allServices)) {
                $services[] = $service;
            }
        }

        $cities = City::whereIn('id', [
            $request->get('ship_city'),
            $request->get('dest_city'),
        ])->get();

        $order = new Order;

        $order->total_price = ''; // todo
        $order->shipping_name = $request->get('cargo')['name'];
        $order->total_weight = $totalWidth;
        $order->ship_city_id = $cities->where('id', $request->get('ship_city'))->first()->id;
        $order->ship_city_name = $cities->where('id', $request->get('ship_city'))->first()->name;
        $order->dest_city_id = $cities->where('id', $request->get('dest_city'))->first()->id;
        $order->dest_city_name = $cities->where('id', $request->get('dest_city'))->first()->name;
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
        $order->discount = $request->get('discount');
        $order->insurance_amount = $request->get('insurance_amount');
        $order->user_id = Auth::user()->id ?? null;
        $order->enter_id = $_COOKIE['enter_id'];

        $payerType = $allTypes->where('class', 'payer_type')
                ->where('slug', $request->get('payer_type'))
                ->first() ?? false;

        if(!$payerType) {
            return abort(500, 'Тип плательщика не найден.');
        }

        $order->payer_type = $payerType->id;
        $order->payer_name = $request->get('payer_name');
        $order->payer_phone = $request->get('payer_phone');

        $orderStatus = $allTypes->where('class', 'order_status')
                ->where('slug', $request->get('status'))
                ->first() ?? false;

        if(!$orderStatus) {
            return abort(500, 'Статус заказа не найден.');
        }

        $order->status_id = $orderStatus->id;
        $order->order_date = Carbon::now();

        $order->save();
        $order->order_items()->saveMany($packages);
        $order->order_services()->attach($services);

        // todo Создание евента

        return redirect()->back()->withSuccess('Заказ успешно сохранён');

//        dd($request->all());

    }
}
