<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Order;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function updateOrder(Request $request) {
        $validator = Validator::make($request->all(), [
            "id" => "required",
            "status" => "required",
            "weight" => "required|numeric",
            "volume" => "required|numeric",
            "price" => "required|numeric",
            "payment_status" => "required|string|in:Оплачен,Не оплачен",
            "cargo_number" => "string",
            "cargo_status" => "string",
        ]);

        if ($validator->fails()) {
            return response(
                [
                    "status" => "error",
                    "text" => $validator->errors()->first()
                ],
                400
            );
        }

        $order = Order::where('code_1c', $request->get('id'))->first();

        if(!isset($order)) {
            return response(
                [
                    "status" => "error",
                    "text" => "Заказ с указанным номером (" . $request->get('id') . ") не найден"
                ],
                400
            );
        }

        $status = Type::firstOrCreate(
            [
                'class' => 'order_status',
                'name' => $request->get('status')
            ]
        );

        $cargoStatus = Type::firstOrCreate(
            [
                'class' => 'cargo_status',
                'name' => $request->get('cargo_status')
            ]
        );

        $paymentStatus = Type::where([
            ['class', 'OrderPaymentStatus'],
            ['name', $request->get('payment_status')],
        ])->firstOrFail();

        $order->status_id = $status->id;
        $order->payment_status_id = $paymentStatus->id;
        $order->actual_weight = $request->get('weight');
        $order->actual_volume = $request->get('volume');
        $order->actual_price = $request->get('price');
        $order->cargo_number = $request->get('cargo_number');
        $order->cargo_status_id = $cargoStatus->id;
        $order->save();

        return [
            "status" => "success"
        ];
    }
}
