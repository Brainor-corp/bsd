<?php

namespace App\Http\Controllers;

use App\City;
use App\Order;
use App\OrderItem;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orderSave(Request $request) {
        dd($request->all());

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

        $allServices = Service::all('id')->pluck('id')->toArray();

        $services = [];
        foreach($request->get('service') as $service) {
            if(in_array($service['id'], $allServices)) {
                $services[] = $service['id'];
            }
        }

        if(!count($packages)) {
            return abort(404);
        }

        $cities = City::whereIn('id', [
            $request->get('ship_city'),
            $request->get('dest_city'),
        ])->get();

        $order = new Order;
        $order->shipping_name = $request->get('cargo')['name'];
        $order->total_weight = $totalWidth;
        $order->ship_city_id = $cities->where('id', $request->get('ship_city'))->first()->id;
        $order->ship_city_name = $cities->where('id', $request->get('ship_city'))->first()->name;
        $order->dest_city_id = $cities->where('id', $request->get('dest_city'))->first()->id;
        $order->dest_city_name = $cities->where('id', $request->get('dest_city'))->first()->name;
        $order->take_need = $request->get('need-to-take') === "on";
        $order->take_address = $request->get('ship_point');

        $order->delivery_need = $request->get('need-to-bring') === "on";
        $order->delivery_address = $request->get('dest_point');


        $order->sender_name = $request->get('sender_name');
        $order->sender_phone = $request->get('sender_phone');
        $order->recepient_name = $request->get('recepient_name');
        $order->recepient_phone = $request->get('recepient_phone');


        $order->discount = $request->get('discount');
        $order->insurance_amount = $request->get('insurance_amount');

        $order->user_id = Auth::user()->id ?? null;
        $order->enter_id = '';

        $order->save();
        $order->order_items()->saveMany($packages);
        $order->order_services()->attach($services);

        return redirect()->back()->withSuccess('Заказ успешно сохранён');

//        dd($request->all());

    }
}
