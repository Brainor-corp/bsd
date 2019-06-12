<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PayKeeperHelper;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function testPaymentPage() {
        return view('v1.pages.test.payment-test');
    }

    public function makePayment(Request $request) {
        # Параметры платежа, сумма - обязательный параметр
        # Остальные параметры можно не задавать
        $payment_data = array (
            "pay_amount" => $request->get('sum'),
            "clientid" => "Иванов Иван Иванович",
            "orderid" => "Заказ № 10",
            "client_email" => "test@example.com",
            "service_name" => "Услуга",
            "client_phone" => "8 (910) 123-45-67"
        );

        if($url = PayKeeperHelper::getPaymentUrl($payment_data)) {
            return redirect($url);
        }

        return redirect()->back();
    }
}
