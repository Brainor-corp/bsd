<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PayKeeperHelper;
use App\Jobs\SendOrderPaymentStatusTo1c;
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
                return $statusQ->where('slug', 'ne-oplachena');
            })
            ->whereHas('payment', function ($statusQ) {
                return $statusQ->where('slug', 'nalichnyy-raschet');
            })
            ->firstOrFail();

        $amount = $order->actual_price ?? ($order->total_price ?? 0);
        $amount = htmlentities($amount, null, 'utf-8');
        $amount = str_replace("&nbsp;", "", $amount);
        $amount = html_entity_decode($amount);

        if(!strlen($amount)) {
            return redirect()->back();
        }

        $amount = floatval($amount);

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
