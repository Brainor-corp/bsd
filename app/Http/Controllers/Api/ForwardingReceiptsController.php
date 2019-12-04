<?php

namespace App\Http\Controllers\Api;

use App\ForwardingReceipt;
use App\Http\Controllers\Controller;
use App\Http\Helpers\EventHelper;
use App\Order;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForwardingReceiptsController extends Controller
{
    public function updateForwardingReceipt(Request $request) {
        $validator = Validator::make($request->all(), [
            "id" => "required",
            "number" => "required|string",
            "cargo_status" => "required|string",
            "date" => "required|date|date_format:Y-m-d",
            "packages_count" => "required|integer",
            "volume" => "required|numeric",
            "weight" => "required|numeric",
            "ship_city" => "required|string",
            "dest_city" => "required|string",
            "sender_name" => "required|string",
            "recipient_name" => "required|string",
            "status" => "required|string",
            "payment_status" => "required|string|in:Оплачен,Не оплачен",
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

        $forwardingReceipt = ForwardingReceipt::where('code_1c', $request->get('id'))->first();

        if(!isset($forwardingReceipt)) {
            return response(
                [
                    "status" => "error",
                    "text" => "ЭР с указанным номером (" . $request->get('id') . ") не найдена"
                ],
                400
            );
        }

        $oldStatusId = $forwardingReceipt->status_id;

        $status = Type::firstOrCreate(
            [
                'class' => 'order_status',
                'name' => $request->get('status')
            ]
        );

        $cargoStatus = null;
        if(!empty($request->get('cargo_status'))) {
            $cargoStatus = Type::firstOrCreate(
                [
                    'class' => 'cargo_status',
                    'name' => $request->get('cargo_status')
                ]
            );
        }

        $paymentStatus = Type::where([
            ['class', 'OrderPaymentStatus'],
            ['name', $request->get('payment_status')],
        ])->firstOrFail();

        $forwardingReceipt->number = $request->get('number');
        $forwardingReceipt->cargo_status_id = $cargoStatus->id ?? null;
        $forwardingReceipt->date = $request->get('date');
        $forwardingReceipt->packages_count = $request->get('packages_count');
        $forwardingReceipt->volume = $request->get('volume');
        $forwardingReceipt->weight = $request->get('weight');
        $forwardingReceipt->ship_city = $request->get('ship_city');
        $forwardingReceipt->dest_city = $request->get('dest_city');
        $forwardingReceipt->sender_name = $request->get('sender_name');
        $forwardingReceipt->recipient_name = $request->get('recipient_name');
        $forwardingReceipt->status_id = $status->id;
        $forwardingReceipt->payment_status_id = $paymentStatus->id;
        $forwardingReceipt->save();

        $newStatusId = $forwardingReceipt->status_id;

        if($oldStatusId !== $newStatusId && !empty($forwardingReceipt->user_id)) {
            EventHelper::createEvent(
                'Изменён статус экспедиторской расписки: ' . $forwardingReceipt->status->name ?? '-',
                null,
                1,
                '/cabinet/orders',
                $forwardingReceipt->user_id
            );
        }

        return [
            "status" => "success"
        ];
    }
}
