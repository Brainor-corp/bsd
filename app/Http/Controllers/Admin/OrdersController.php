<?php

namespace App\Http\Controllers\Admin;

use App\ContactEmail;
use App\Http\Helpers\Api1CHelper;
use App\Http\Helpers\OrderHelper;
use App\Mail\OrderCreated;
use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{
    public function resendAdminEmail(Request $request)
    {
        $order = Order::find($request->get('order_id'));
        $emails = ContactEmail::where('active', true)->get();

        try {
            foreach($emails as $email) {
                Mail::to($email->email)->send(new OrderCreated($order, $email->email));
            }

            return response('E-Mail успешно отправлен.', 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    public function resendTo1c(Request $request)
    {
        $order = Order::find($request->get('order_id'));

        try {
            $sendOrder = OrderHelper::orderTo1cFormat($order);

            $response1c = Api1CHelper::post('create_order', $sendOrder);
            if(
                $response1c['status'] == 200 &&
                $response1c['response']['status'] === 'success' &&
                !empty($response1c['response']['id'])
            ) {
                DB::table('orders')->where('id', $sendOrder['Идентификатор_на_сайте'])->update([
                    'code_1c' => $response1c['response']['id'],
                    'sync_need' => false,
                    'send_error' => false
                ]);

                return 'Заявка успешно отправлена в 1с.';
            } else {
                return response('При отправке заявки в 1с возникла ошибка.', 500);
            }
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    public function resendToEmail(Request $request)
    {
        $order = Order::find($request->get('order_id'));

        try {
            Mail::to($request->get('email'))->send(new OrderCreated($order, $request->get('email')));

            return response('E-Mail успешно отправлен.', 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
