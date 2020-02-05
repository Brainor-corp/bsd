<?php

namespace App\Http\Controllers;

use App\Counterparty;
use App\Http\Helpers\Api1CHelper;
use App\Http\Helpers\OrderHelper;
use App\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Api1cTestController extends Controller
{
    public function newUser(Request $request) {
        $user = User::where('id', $request->get('id'))->first();
        $send = [
            'email' => $user->email,
            'tel' => intval($user->phone)
        ];

        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'new_user',
            $send
        );

        if(
            $response1c['status'] == 200
            && !empty($response1c['response']['id'])
            && !empty($response1c['response']['status'])
            && $response1c['response']['status'] === 'success'
            && $response1c['response']['id'] !== 'not found'
        ) {
            DB::table('users')->where('id', $user->id)->update([
                'guid' => $response1c['response']['id'],
                'sync_need' => false
            ]);
        }

        dd([
            'send' => [],
            'response' => $response1c
        ]);
    }

    public function createOrder(Request $request) {
        $order = Order::where('id', $request->get('id'))->first();

        $sendOrder = OrderHelper::orderTo1cFormat($order);

        $response1c = Api1CHelper::post('create_order', $sendOrder, true);

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
        }

        dd(
            [
                'send' => $sendOrder,
                'response' => $response1c
            ]
        );
    }

    public function documentList(Request $request) {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'document_list',
            [
                "user_id" => $request->get('user_id') ?? "f008aa7f-29d6-11e9-80c7-000d3a396ad2",
                "order_id" => $request->get('order_id') ?? "2ef09a62-8dbb-11e9-a688-001c4208e0b2"
            ]
        );

        dd($response1c);
    }

    public function documentById(Request $request) {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'document/id',
            [
                "user_id" => $request->get('user_id') ?? "f008aa7f-29d6-11e9-80c7-000d3a396ad2",
                "document_id" => $request->get('document_id') ?? "f22b5b40-3c29-11e9-80f7-000d3a396ad2",
                "type" => intval($request->get('type')) ?? 5,
                "empty_fields" => true
            ]
        );

        dd($response1c);
    }

    private static $host = "http://s4.tkbsd.ru/copy/hs/rest/";
    public function printForm(Request $request) {
        $send = [
            "user_id" => $request->get('user_id') ?? "14b3b98b-14a1-11e9-a98f-000d3a28f168",
            "document_id" => $request->get('document_id') ?? "bb3926c6-e417-11e9-8c89-001c42a74df3",
            "type" => 5,
            "empty_fields" => true
        ];

        $result = Api1CHelper::getPdf(
            'print_form',
            $send
        );

        header('Cache-Control: public');
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="new.pdf"');
        header('Content-Length: '.strlen($result));

        echo $result;
    }

    public function documentByNumber() {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'document/number',
            [
                "user_id" => "f008aa7f-29d6-11e9-80c7-000d3a396ad2",
                "number" => "СП00-005673",
                "year" => 2019,
                "type" => 4,
                "empty_fields" => false
            ]
        );

        dd($response1c);
    }

    public function orders(Request $request) {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'orders',
            [
                'user_id' => $request->get('user_id') ?? "f008aa7f-29d6-11e9-80c7-000d3a396ad2",
            ]
        );

        dd($response1c);
    }

    public function contract(Request $request) {
        $send = [
            "user_id" => $request->get('guid') ?? 'e9795c33-97f7-11e8-a972-000d3a28f168',
        ];

        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'client/contract',
            $send
        );

        dd([
            'send' => $send,
            'response' => $response1c
        ]);
    }

    public function discount(Request $request) {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'client/discount',
            [
                "user_id" => $request->get('guid') ?? 'e9795c33-97f7-11e8-a972-000d3a28f168',
            ]
        );

        dd($response1c);
    }

    public function newClient(Request $request) {
        $counterparty = $request->get('id') ?
            Counterparty::where('id', $request->get('id'))->firstOrFail() :
            Counterparty::orderBy('created_at', 'desc')->first();

        $sendData = [
            "ПравоваяФорма" => $counterparty->legal_form ?? '',
            "НаименованиеПолное" => $counterparty->company_name ?? '',
            "ЮридическоеФизическоеЛицо" => $counterparty->type->slug === 'fizicheskoe-lico' ? "ФизическоеЛицо" : "ЮридическоеЛицо",
            "ИНН" => $counterparty->inn ?? '',
            "КПП" => $counterparty->kpp ?? '',
            "ДокументУдостоверяющийЛичность" => strval($counterparty->passport_series . $counterparty->passport_number) ?? null,
            "ОсновноеКонтактноеЛицо" => $counterparty->contact_person ?? '',
            "Комментарий" => $counterparty->addition_info ?? '',
            "ДатаСоздания" => $counterparty->created_at->format('Y-m-d\TH:i:s'),
            "ТелефонЗначениеJSON" => [
                "type" => "Телефон",
                "value" => intval($counterparty->phone),
                "CountryCode" => "",
                "AreaCode" => "",
                "Number" => $counterparty->phone,
                "НомерТелефонаБезКодов" => mb_substr($counterparty->phone, -7)
            ],
            "ЮридическийАдресЗначениеJSON" => [
                "type" => "Адрес",
                "value" => $counterparty->legal_address ?? "",
                "Страна" => "",
                "Город" => $counterparty->legal_address_city ?? "",
                "НомерТелефона" => "",
                "НомерТелефонаБезКодов" => ""
            ],
        ];
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'new_client',
            $sendData
        );

        if(isset($response1c['response']['status']) && $response1c['response']['status'] === "success") {
            $counterparty->code_1c = $response1c['response']['id'];
            $counterparty->save();
        }

        dd([
            'send' => $sendData,
            'response' => $response1c
        ]);
    }

    public function clientById(Request $request) {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'client/by_id',
            [
                "user_id" => $request->get('user_id') ?? "d154781b-1a29-11e9-a994-000d3a28f168",
            ]
        );

        dd($response1c);
    }

    public function updateOrderPaymentStatus(Request $request) {
        $order = !empty($request->get('id')) ?
            Order::where('id', $request->get('id'))->firstOrFail() :
            Order::orderBy('created_at', 'desc')->firstOrFail();

        $data = [
            'order_id' => $order->code_1c,
            'user_id' => $order->user->guid ?? '',
            'status' => $order->payment_status->name ?? ''
        ];

        $response1c = Api1CHelper::post('order/update_payment_status', $data);

        dd([
            'response' => $response1c,
            'data' => $data
        ]);

        if(
            $response1c['status'] == 200 &&
            $response1c['response']['status'] === 'success'
        ) {
            DB::table('orders')->where('id', $order->id)->update([
                'payment_sync_need' => false
            ]);
        }
    }
}
