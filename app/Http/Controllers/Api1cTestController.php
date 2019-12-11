<?php

namespace App\Http\Controllers;

use App\Counterparty;
use App\Http\Helpers\Api1CHelper;
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

        $sendOrder = [];

        $sendOrder['Идентификатор_на_сайте'] = intval($order->id);
        $sendOrder['Название_груза'] = $order->shipping_name ?? "";
        $sendOrder['Общий_вес'] = floatval($order->total_weight);
        $sendOrder['Общий_объем'] = floatval($order->total_volume);
        $sendOrder['Примечания'] = $order->cargo_comment ?? '';

        if(isset($order->order_date) && isset($order->ship_time_from) && isset($order->ship_time_to)) {
            $sendOrder['Время_доставки'] = [
                'День' => Carbon::createFromFormat('Y-m-d H:i:s', $order->order_date)->format('Y-m-d'),
                'Время_с' => Carbon::createFromFormat('H:i:s', $order->ship_time_from)->format('H:i'),
                'Время_по' => Carbon::createFromFormat('H:i:s', $order->ship_time_to)->format('H:i')
            ];
        }

        $sendOrder['Количество_мест'] = array_sum(array_column($order->order_items->toArray(), 'quantity'));
        $sendOrder['Итоговая_цена'] = is_numeric($order->total_price) ? intval($order->total_price) : 0;
        $sendOrder['СтатусОплаты'] = $order->payment_status->name ?? '';
        $sendOrder['Базовая_цена_маршрута'] = is_numeric($order->base_price) ? intval($order->base_price) : 0;
        $sendOrder['Заявку_заполнил'] = $order->order_creator;
        $sendOrder['Тип_заполнителя_заявки'] = $order->order_creator_type_model->name ?? '';

        $sendOrder['Страховка'] = [
            'Сумма_страховки' => is_numeric($order->insurance) ? intval($order->insurance) : 0,
            'Цена_страхования' => is_numeric($order->insurance_amount) ? intval($order->insurance_amount) : 0,
        ];

        $sendOrder['Скидка'] = [
            'Процент_скидки' => is_numeric($order->discount) ? intval($order->discount) : 0,
            'Сумма_скидки' => is_numeric($order->discount_amount) ? intval($order->discount_amount) : 0,
        ];

        $sendOrder['Идентификатор_пользователя_на_сайте'] = intval($order->user_id) ?? null;
        $sendOrder['Идентификатор_пользователя_в_1с'] = $order->user->guid ?? '';
        $sendOrder['Способ_оплаты'] = $order->payment->name ?? "";

        if(!empty($order->code_1c)) {
            $sendOrder['Идентификатор_1С'] = $order->code_1c;
        }

        $sendOrder['Дата_и_время_создания_заказа'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('Y-m-d\TH:i:s');

        if(isset($order->order_finish_date)) {
            $sendOrder['Дата_и_время_завершения_заказа'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->order_finish_date)->format('Y-m-d\TH:i:s');
        }

        if(isset($order->order_services)) {
            $sendOrder['Услуги'] = $order->order_services->map(function ($order_service) {
                return [
                    'Идентификатор_на_сайте' => $order_service->id,
                    'Название' => $order_service->name ?? "",
                    'Просчитанная_цена' => is_numeric($order_service->pivot->price) ? intval($order_service->pivot->price) : 0,
                ];
            })->toArray();
        }

        $sendOrder['Город_отправления'] = [
            'Идентификатор_на_сайте' => intval($order->ship_city->kladr_id),
            'Название' => $order->ship_city->name ?? ""
        ];

        $sendOrder['Город_назначения'] = [
            'Идентификатор_на_сайте' => intval($order->dest_city->kladr_id),
            'Название' => $order->dest_city->name ?? ""
        ];

        $sendOrder['Статус_заказа'] = $order->status->name ?? "";

        $sendOrder['Забор_груза'] = [
            'Флаг_необходимости' => !!intval($order->take_need),
            'Экспедиция_в_пределах_города' => !!intval($order->take_in_city),
            'Адрес_экспедиции' => $order->take_address ?? "",
            'Дистанция_экспедиции' => strval($order->take_distance),
            'Точная_экспедиция' => !!intval($order->take_point),
            'Просчитанная_цена' => is_numeric($order->take_price) ? floatval($order->take_price) : 0,
            'Название_города_экспедиции' => $order->take_city_name ?? "",
        ];

        $sendOrder['Доставка_груза'] = [
            'Флаг_необходимости' => !!intval($order->delivery_need),
            'Экспедиция_в_пределах_города' => !!intval($order->delivery_in_city),
            'Адрес_экспедиции' => $order->delivery_address ?? "",
            'Дистанция_экспедиции' => strval($order->delivery_distance),
            'Точная_экспедиция' => !!intval($order->delivery_point),
            'Просчитанная_цена' => is_numeric($order->delivery_price) ? intval($order->delivery_price) : 0,
            'Название_города_экспедиции' => $order->delivery_city_name ?? "",
        ];

        $sendOrder['Пакеты'] = $order->order_items->map(function ($order_item) {
            return [
                "Идентификатор_на_сайте" => $order_item->id,
                "Длина" => floatval($order_item->length),
                "Ширина" => floatval($order_item->width),
                "Высота" => floatval($order_item->height),
                "Объем" => floatval($order_item->volume),
                "Вес" => floatval($order_item->weight),
                "Количество" => intval($order_item->quantity),
            ];
        })->toArray();

        $sendOrder['Отправитель'] = [
            'Правовая_форма' => $order->sender_legal_form ?? "",
            'Наименование' => '---',
            'Адрес' => [
                'Город' => $order->sender_legal_address_city ?? "",
                'Адрес' => $order->sender_legal_address ?? ""
            ],
            'ИНН' => strlen($order->sender_inn) >= 0 && strlen($order->sender_inn) <= 12 ? strval($order->sender_inn) : "",
            'КПП' => strlen($order->sender_kpp) >= 0 && strlen($order->sender_kpp) <= 9 ? strval($order->sender_kpp) : "",
            'Контактное_лицо' => $order->sender_contact_person ?? "",
            'Телефон' => strlen($order->sender_phone) >= 0 && strlen($order->sender_phone) <= 11 ? strval($order->sender_phone) : "",
            'Дополнительная_информация' => $order->sender_addition_info ?? "",
            'Серия_паспорта' => strlen($order->sender_passport_series) >= 0 && strlen($order->sender_passport_series) <= 4 ? strval($order->sender_passport_series) : "",
            'Номер_паспорта' => strlen($order->sender_passport_number) >= 0 && strlen($order->sender_passport_number) <= 6 ? strval($order->sender_passport_number) : "",
        ];

        if(isset($order->sender_type->name)) {
            $sendOrder['Отправитель']['Тип_контрагента'] = $order->sender_type->name;

            if($order->sender_type->slug === 'fizicheskoe-lico') {
                $sendOrder['Отправитель']['Наименование'] = $order->sender_name ?? "";
            } else {
                $sendOrder['Отправитель']['Наименование'] = strlen($order->sender_company_name) >= 3 ? $order->sender_company_name : "---";
            }
        }

        $sendOrder['Получатель'] = [
            'Правовая_форма' => $order->recipient_legal_form ?? "",
            'Наименование' => "---",
            'Адрес' => [
                'Город' => $order->recipient_legal_address_city ?? "",
                'Адрес' => $order->recipient_legal_address ?? ""
            ],
            'ИНН' => strlen($order->recipient_inn) >= 0 && strlen($order->recipient_inn) <= 12 ? strval($order->recipient_inn) : "",
            'КПП' => strlen($order->recipient_kpp) >= 0 && strlen($order->recipient_kpp) <= 9 ? strval($order->recipient_kpp) : "",
            'Контактное_лицо' => $order->recipient_contact_person ?? "",
            'Телефон' => strlen($order->recipient_phone) >= 0 && strlen($order->recipient_phone) <= 11 ? strval($order->recipient_phone) : "",
            'Дополнительная_информация' => $order->recipient_addition_info ?? "",
            'Серия_паспорта' => strlen($order->recipient_passport_series) >= 0 && strlen($order->recipient_passport_series) <= 4 ? strval($order->recipient_passport_series) : "",
            'Номер_паспорта' => strlen($order->recipient_passport_number) >= 0 && strlen($order->recipient_passport_number) <= 6 ? strval($order->recipient_passport_number) : "",
        ];

        if(isset($order->recipient_type->name)) {
            $sendOrder['Получатель']['Тип_контрагента'] = $order->recipient_type->name;

            if($order->recipient_type->slug === 'fizicheskoe-lico') {
                $sendOrder['Получатель']['Наименование'] = $order->recipient_name ?? "";
            } else {
                $sendOrder['Получатель']['Наименование'] = strlen($order->recipient_company_name) >= 3 ? $order->recipient_company_name : "---";
            }
        }

        switch($order->payer->name) {
            case "Отправитель":
                $sendOrder['Плательщик'] = [
                    'Тип_контрагента' => $sendOrder['Отправитель']['Тип_контрагента'] ?? '',
                    'Правовая_форма' => $sendOrder['Отправитель']['Правовая_форма'] ?? '',
                    'Наименование' => $sendOrder['Отправитель']['Наименование'] ?? '',
                    'Адрес' => $sendOrder['Отправитель']['Адрес'] ?? '',
                    'ИНН' => $sendOrder['Отправитель']['ИНН'] ?? '',
                    'КПП' => $sendOrder['Отправитель']['КПП'] ?? '',
                    'Контактное_лицо' => $sendOrder['Отправитель']['Контактное_лицо'] ?? '',
                    'Телефон' => $sendOrder['Отправитель']['Телефон'] ?? '',
                    'Дополнительная_информация' => $sendOrder['Отправитель']['Дополнительная_информация'] ?? '',
                    'Серия_паспорта' => $sendOrder['Отправитель']['Серия_паспорта'] ?? '',
                    'Номер_паспорта' => $sendOrder['Отправитель']['Номер_паспорта'] ?? '',
                ];
                break;

            case "Получатель":
                $sendOrder['Плательщик'] = [
                    'Тип_контрагента' => $sendOrder['Получатель']['Тип_контрагента'] ?? '',
                    'Правовая_форма' => $sendOrder['Получатель']['Правовая_форма'] ?? '',
                    'Наименование' => $sendOrder['Получатель']['Наименование'] ?? '',
                    'Адрес' => $sendOrder['Получатель']['Адрес'] ?? '',
                    'ИНН' => $sendOrder['Получатель']['ИНН'] ?? '',
                    'КПП' => $sendOrder['Получатель']['КПП'] ?? '',
                    'Контактное_лицо' => $sendOrder['Получатель']['Контактное_лицо'] ?? '',
                    'Телефон' => $sendOrder['Получатель']['Телефон'] ?? '',
                    'Дополнительная_информация' => $sendOrder['Получатель']['Дополнительная_информация'] ?? '',
                    'Серия_паспорта' => $sendOrder['Получатель']['Серия_паспорта'] ?? '',
                    'Номер_паспорта' => $sendOrder['Получатель']['Номер_паспорта'] ?? '',
                ];
                break;

            default:
                $sendOrder['Плательщик']['Тип_контрагента'] = $order->payer_form_type->name;
                $sendOrder['Плательщик'] = [
                    'Правовая_форма' => $order->payer_legal_form ?? "",
                    'Наименование' => "---",
                    'Адрес' => [
                        'Город' => $order->payer_legal_address_city ?? "",
                        'Адрес' => $order->payer_legal_address ?? ""
                    ],
                    'ИНН' => strlen($order->payer_inn) >= 0 && strlen($order->payer_inn) <= 12 ? strval($order->payer_inn) : "",
                    'КПП' => strlen($order->payer_kpp) >= 0 && strlen($order->payer_kpp) <= 9 ? strval($order->payer_kpp) : "",
                    'Контактное_лицо' => $order->payer_contact_person ?? "",
                    'Телефон' => strlen($order->payer_phone) >= 0 && strlen($order->payer_phone) <= 11 ? strval($order->payer_phone) : "",
                    'Дополнительная_информация' => $order->payer_addition_info ?? "",
                    'Серия_паспорта' => strlen($order->payer_passport_series) >= 0 && strlen($order->payer_passport_series) <= 4 ? strval($order->payer_passport_series) : "",
                    'Номер_паспорта' => strlen($order->payer_passport_number) >= 0 && strlen($order->payer_passport_number) <= 6 ? strval($order->payer_passport_number) : "",
                ];

                if($order->payer_form_type->slug === 'fizicheskoe-lico') {
                    $sendOrder['Плательщик']['Наименование'] = $order->payer_name ?? "";
                } else {
                    $sendOrder['Плательщик']['Наименование'] = strlen($order->payer_company_name) >= 3 ? $order->payer_company_name : "---";
                }
                break;
        }

        $sendOrder['Плательщик']['Email_плательщика'] = $order->payer_email ?? '';
        $sendOrder['Плательщик']['Тип_плательщика'] = $order->payer->name ?? '';

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
