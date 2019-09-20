<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PayKeeperHelper;
use App\Order;

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
            "orderid" => "Заказ № $order->id",
            "client_email" => $order->payer->email ?? '',
            "service_name" => "Услуга",
            "client_phone" => $order->payer->phone ?? ''
        );

        if($url = PayKeeperHelper::getPaymentUrl($payment_data)) {
            return redirect($url);
        }

        return redirect()->back();
    }
}
