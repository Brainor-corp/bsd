<?php

namespace App\Http\Controllers;

use App\ForwardingReceipt;
use App\Http\Helpers\PayKeeperHelper;
use App\Jobs\SendOrderPaymentStatusTo1c;
use App\Order;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function testPaymentPage() {
        return view('v1.pages.test.payment-test');
    }

    public function makePayment($document_id) {
//        $response1c = \App\Http\Helpers\Api1CHelper::post(
//            'invoice',
//            [
//                'user_id' => $order->user->guid ?? '',
//                'document_id' => $document_id ?? ''
//            ]
//        );
//
//        if(
//            $response1c['status'] == 200
//            && !empty($response1c['response']['status'])
//            && $response1c['response']['status'] === 'success'
//            && !empty($response1c['response']['data'])
//        ) {
//            $data = $response1c['response']['data'];
//        }

        $data = [
            [
                "name" => 'Позиция 1',
                "price" => 1.01,
                "quantity" => 3,
                "sum" => 3.03,
                "tax" => 'vat10',
                "tax_sum" => 0.28,
                "item_type" => "service"
            ],
            [
                "name" => 'Позиция 2',
                "price" => 1.01,
                "quantity" => 3,
                "sum" => 3.03,
                "tax" => 'vat10',
                "tax_sum" => 0.28,
                "item_type" => "work"
            ]
        ];

        $amount = array_sum(array_column($data, 'sum'));
        $user = Auth::user();

        # Параметры платежа, сумма - обязательный параметр
        # Остальные параметры можно не задавать
        $payment_data = array (
            "pay_amount" => $amount,
            "clientid" => $user->guid ?? '',
            "orderid" => $document_id,
            "client_email" => $user->email ?? '',
            "client_phone" => $user->phone ?? '',
            "service_name" => ";PKC|".json_encode($data)."|", # передача корзины товаров для формирования чека по 54-ФЗ
        );

        if($url = PayKeeperHelper::getPaymentUrl($payment_data)) {
            return redirect($url);
        }

        return redirect()->back();
    }

    public function updateOrder(Request $request) {
        $secret_seed = env('PAYKEEPER_SECRET');
        $id = $request->get('id');
        $sum = $request->get('sum');
        $clientid = $request->get('clientid');
        $orderid = $request->get('orderid');
        $key = $request->get('key');

        if ($key != md5 ($id.number_format($sum, 2, ".", "")
                .$clientid.$orderid.$secret_seed))
        {
            echo "Error! Hash mismatch";
            exit;
        }

        $status = Type::where([
            ['class', 'OrderPaymentStatus'],
            ['slug', 'oplachena']
        ])->firstOrFail();

        Order::where('id', $orderid)->update([
            'payment_id' => $id,
            'payment_status_id' => $status->id,
            'payment_sync_need' => true
        ]);

        SendOrderPaymentStatusTo1c::dispatch(Order::where('id', $orderid)->first());

        echo "OK ".md5($id.$secret_seed);
    }
}
