<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PayKeeperHelper;
use App\Order;
use App\Type;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function testPaymentPage() {
        return view('v1.pages.test.payment-test');
    }

    public function makePayment($order_id) {
        $order = Order::available()
            ->where('id', $order_id)
            ->whereHas('status', function ($statusQ) {
                return $statusQ->where('slug', 'ispolnyaetsya');
            })
            ->whereHas('payment_status', function ($statusQ) {
                return $statusQ->where('slug', 'ne-oplachen');
            })
            ->whereHas('payment', function ($statusQ) {
                return $statusQ->where('slug', 'nalichnyy-raschet');
            })
            ->firstOrFail();

        $amount = $order->actual_price ?? ($order->total_price ?? 0);
        if(!$amount) {
            return redirect()->back();
        }

        # Параметры платежа, сумма - обязательный параметр
        # Остальные параметры можно не задавать
        $payment_data = array (
            "pay_amount" => $amount,
            "clientid" => $order->payer->guid ?? '',
            "orderid" => "$order->id",
            "client_email" => $order->payer->email ?? '',
            "service_name" => "Услуга",
            "client_phone" => $order->payer->phone ?? ''
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

        $order = Order::where('id', $orderid)->firstOrFail();
        $status = Type::where([
            ['class', 'OrderPaymentStatus'],
            ['slug', 'oplachen']
        ])->firstOrFail();

        $order->update([
            'payment_id' => $id,
            'payment_status_id' => $status->id
        ]);

        echo "OK ".md5($id.$secret_seed);
    }
}
