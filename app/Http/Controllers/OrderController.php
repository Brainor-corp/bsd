<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Helpers\CalculatorHelper;
use App\Order;
use App\OrderItem;
use App\Route;
use App\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class OrderController extends Controller
{
    public function orderSave(Request $request) {
        $cities = City::whereIn('id', [
            $request->get('ship_city'),
            $request->get('dest_city')
        ])->get();

        if(count($cities) < 2) {
            return abort(500, "Город(а) маршрута не найден(ы).");
        }

        $totalWeight = 0;
        $totalVolume = 0;

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

        $calculatedData = CalculatorHelper::getAllCalculatedData(
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
                    'distance' => $request->get('take_distance')
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
                    'distance' => $request->get('bring_distance')
                ] : [],
            $request->get('insurance_amount'),
            $request->get('discount')
        );

        if(!$calculatedData) {
            return abort(400);
        }

        $allTypes = Type::where('class', 'payer_type')
            ->orWhere('class', 'payment_type')
            ->orWhere(function ($q) {
                return $q->where('class', 'order_status')
                    ->whereIn('slug', ['chernovik', 'ozhidaet-moderacii']);
            })
            ->get();

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

        $order = null;
        if($request->get('order_id')) {
            $order = Order::where('id', $request->get('order_id'))
                ->where(function ($orderQuery) {
                    return Auth::check() ? $orderQuery
                        ->where('user_id', Auth::user()->id)
                        ->orWhere('enter_id', $_COOKIE['enter_id']) :
                        $orderQuery->where('enter_id', $_COOKIE['enter_id']);
                })->firstOrFail();
        }

        $shipCity = $cities->where('id', $request->get('ship_city'))->first();
        $destCity = $cities->where('id', $request->get('dest_city'))->first();

        $route = Route::where([
            ['ship_city_id', $shipCity->id],
            ['dest_city_id', $destCity->id],
        ])->firstOrFail();

        if(!isset($order)) {
            $order = new Order;
        }

        $order->total_price = $calculatedData['total'];
        $order->base_price = $calculatedData['route']['price'];
        $order->shipping_name = $request->get('cargo')['name'];
        $order->total_weight = $totalWeight;
        $order->ship_city_id = $shipCity->id;
        $order->ship_city_name = $shipCity->name;
        $order->dest_city_id = $destCity->id;
        $order->estimated_delivery_date = Carbon::now()->addDays($route->delivery_time)->toDateString();
        $order->dest_city_name = $destCity->name;
        $order->take_need = $request->get('need-to-take') === "on"; // Нужен ли забор груза
        $order->delivery_need = $request->get('need-to-bring') === "on"; // Нужна ли доставка груза
        $order->sender_name = $request->get('sender_name');
        $order->sender_phone = $request->get('sender_phone');
        $order->recepient_name = $request->get('recepient_name');
        $order->recepient_phone = $request->get('recepient_phone');
        $order->discount = $request->get('discount');
        $order->discount_amount = $calculatedData['discount'] ?? 0;
        $order->insurance = $request->get('insurance_amount');
        $order->insurance_amount = end($calculatedData['services'])['total'] ?? 0;
        $order->user_id = Auth::user()->id ?? null;
        $order->enter_id = $_COOKIE['enter_id'];
        $order->payer_type = $payerType->id;
        $order->payer_name = $request->get('payer_name');
        $order->payer_phone = $request->get('payer_phone');
        $order->payment_type = $paymentType->id;
        $order->status_id = $orderStatus->id;
        $order->order_date = Carbon::now();

        if($request->get('need-to-take') === "on") {
            $order->take_point = $request->get('ship-from-point') === "on";
            $order->take_in_city = $request->get('need-to-take-type') === "in";

            // Если забор груза за пределами города
            if($request->get('need-to-take-type') === "from") {
                $order->take_address = $request->get('ship_point'); // Адрес забора
                $order->take_city_name = $calculatedData['delivery']['take']['city_name']; // Город забора
                $order->take_distance = $calculatedData['delivery']['take']['distance']; // Дистанция от города отправки до адреса забора
            } else { // Если забор в пределах города
                $order->take_address = null;
                $order->take_distance = null;
                $order->take_city_name = $shipCity->name; // Город забора
            }

            $order->take_price = $calculatedData['delivery']['take']['price']; // Цена забора
        } else {
            $order->take_point = false;
            $order->take_in_city = false;
            $order->take_address = null;
            $order->take_city_name = null;
            $order->take_distance = null;
            $order->take_price = null;
        }

        if($request->get('need-to-bring') === "on") {
            $order->delivery_point = $request->get('bring-to-point') === "on";
            $order->delivery_in_city = $request->get('need-to-bring-type') === "in";

            // Если доставка за пределами города
            if($request->get('need-to-bring-type') === "from") {
                $order->delivery_address = $request->get('dest_point'); // Адрес доставки
                $order->delivery_city_name = $calculatedData['delivery']['bring']['city_name']; // Город доставки
                $order->delivery_distance = $calculatedData['delivery']['bring']['distance']; // Дистанция от города назначения до адреса доставки
            } else { // Если доставка в пределах города
                $order->delivery_address = null;
                $order->delivery_distance = null;
                $order->delivery_city_name = $destCity->name; // Город забора
            }

            $order->delivery_price = $calculatedData['delivery']['bring']['price']; // Цена доставки
        } else {
            $order->delivery_point = false;
            $order->delivery_in_city = false;
            $order->delivery_address = null;
            $order->delivery_city_name = null;
            $order->delivery_distance = null;
            $order->delivery_price = null;
        }

        $servicesToSync = [];
        foreach($calculatedData['services'] as $service) {
            if(!isset($service['id'])) {
                continue;
            }

            $servicesToSync[$service['id']] = [
                'price' => $service['total']
            ];
        }

        $order->save();

        $order->order_items()->delete();
        $order->order_items()->saveMany($packages);

        $order->order_services()->sync($servicesToSync);

        return $order->status->slug === "chernovik" ?
            redirect(route('calculator-show', ['id' => $order->id])) :
            redirect(route('report-show', ['id' => $order->id]));
    }

    public function shipmentSearch(Request $request){
        $order = Order::with('status')
            ->whereDoesntHave('status', function ($statusQuery) {
                return $statusQuery->where('slug', "chernovik");
            })
            ->find($request->get('order_id'));

        return View::make('v1.pages.shipment-status.status-page')->with(compact('order'));
    }

    public function searchOrders(Request $request) {
        $orders = Order::with('status', 'ship_city', 'dest_city')
            ->where(function ($ordersQuery) {
                return Auth::check() ? $ordersQuery
                    ->where('user_id', Auth::user()->id)
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $ordersQuery->where('enter_id', $_COOKIE['enter_id']);
            })
            ->when($request->get('id'), function ($order) use ($request) {
                return $order->where('id', 'LIKE', '%' . $request->get('id') . '%');
            })
            ->when($request->finished == 'true', function ($order) use ($request) {
                return $order->whereHas('status', function ($type) {
                    return $type->where('slug', 'dostavlen');
                });
            })
            ->when($request->finished == 'false' && $request->get('status'), function ($order) use ($request) {
                return $order->where('status_id', $request->get('status'));
            })
            ->get();

        return View::make('v1.partials.profile.orders')->with(compact('orders'))->render();
    }

    public function actionGetOrderItems(Request $request) {
        $order = Order::where('id', $request->order_id)
            ->where(function ($orderQuery) {
                return Auth::check() ? $orderQuery
                    ->where('user_id', Auth::user()->id)
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $orderQuery->where('enter_id', $_COOKIE['enter_id']);
            })
            ->with('order_items')->firstOrFail();
        return View::make('v1.partials.profile.order-items-modal-body')->with(compact('order'))->render();
    }

    public function actionGetOrderSearchInput() {
        $types = Type::where('class', 'order_status')->get();
        return View::make('v1.partials.profile.order-search-select')->with(compact('types'))->render();
    }
}
