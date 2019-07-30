<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Order;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function updateOrderStatus(Request $request) {
        $validator = Validator::make($request->all(), [
            "id" => "required",
            "status" => "required",
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

        $status = Type::where([
            ['class', 'order_status'],
            ['name', $request->get('status')]
        ])->first();

        if(!isset($status)) {
            $status = new Type();
            $status->name = $request->get('status');
            $status->class = 'order_status';
            $status->save();
        }

        $order->status_id = $status->id;
        $order->save();

        return [
            "status" => "success"
        ];
    }
}
