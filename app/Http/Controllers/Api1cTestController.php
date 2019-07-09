<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Api1CHelper;
use App\Order;
use Illuminate\Support\Facades\DB;

class Api1cTestController extends Controller
{
    public function newUser() {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'new_user',
            [
                'email' => "abegunov@mail.ru",
                'tel' => 79817397557
            ]
        );

        dd($response1c);
    }

    public function createOrder() {
        $orders = Order::where('sync_need', true)
            ->whereHas('status', function ($statusQ) {
                return $statusQ->where('slug', '<>', 'chernovik');
            })
            ->with(
                'recipient_type',
                'sender_type',
                'payer_form_type',
                'payer',
                'payment',
                'order_services',
                'ship_city',
                'dest_city',
                'status',
                'order_items'
            )->get()->toArray();

        // Преобразуем данные модели в вид, необходимый для отправки в 1с
        $orders = array_map(function ($order) {
            $mapOrder = [];

            $mapOrder['Идентификатор_на_сайте'] = $order['id'] ?? null;
            $mapOrder['Название_груза'] = $order['shipping_name'] ?? null;
            $mapOrder['Общий_вес'] = $order['total_weight'] ?? null;
            $mapOrder['Время_доставки'] = $order['order_date'] ?? null;
            $mapOrder['Итоговая_цена'] = $order['total_price'] ?? null;
            $mapOrder['Базовая_цена_маршрута'] = $order['base_price'] ?? null;

            $mapOrder['Страховка'] = [
                'Сумма_страховки' => $order['insurance'] ?? null,
                'Цена_страхования' => $order['insurance_amount'] ?? null
            ];

            $mapOrder['Скидка'] = [
                'Процент_скидки' => $order['discount'] ?? null,
                'Сумма_скидки' => $order['discount_amount'] ?? null
            ];

            $mapOrder['Идентификатор_пользователя_на_сайте'] = $order['user_id'] ?? null;
            $mapOrder['Способ_оплаты'] = $order['payment']['name'] ?? null;
            $mapOrder['Идентификатор_1С'] = $order['code_1c'] ?? null;
            $mapOrder['Дата_и_время_создания_заказа'] = $order['created_at'] ?? null;
            $mapOrder['Дата_и_время_завершения_заказа'] = $order['order_finish_date'] ?? null;

            $mapOrder['Плательщик'] = [
                'Тип_плательщика' => $order['payer']['name'] ?? null,
                'Тип_контрагента' => $order['payer_form_type']['name'] ?? null,
                'Правовая_форма' => $order['payer_legal_form'] ?? null,
                'Название_компании' => $order['payer_company_name'] ?? null,
                'Адрес' => [
                    'Город' => $order['payer_legal_address_city'] ?? null,
                    'Улица' => $order['payer_legal_address_street'] ?? null,
                    'Дом' => $order['payer_legal_address_house'] ?? null,
                    'Корпус' => $order['payer_legal_address_block'] ?? null,
                    'Строение' => $order['payer_legal_address_building'] ?? null,
                    'Квартира_офис' => $order['payer_legal_address_apartment'] ?? null,
                ],
                'ИНН' => $order['payer_inn'] ?? null,
                'КПП' => $order['payer_kpp'] ?? null,
                'Имя' => $order['payer_name'] ?? null,
                'Контактное_лицо' => $order['payer_contact_person'] ?? null,
                'Телефон' => $order['payer_phone'] ?? null,
                'Дополнительная_информация' => $order['payer_addition_info'] ?? null,
                'Серия_паспорта' => $order['payer_passport_series'] ?? null,
                'Номер_паспорта' => $order['payer_passport_number'] ?? null,
            ];

            $mapOrder['Услуги'] = array_map(function ($order_service) {
                return [
                    'Идентификатор_на_сайте' => $order_service['id'] ?? null,
                    'Название' => $order_service['name'] ?? null,
                    'Просчитанная_цена' => $order_service['pivot']['price'] ?? null,
                ];
            }, $order['order_services']);

            $mapOrder['Город_отправления'] = [
                'Идентификатор_на_сайте' => $order['ship_city']['id'] ?? null,
                'Название' => $order['ship_city']['name'] ?? null
            ];

            $mapOrder['Город_назначения'] = [
                'Идентификатор_на_сайте' => $order['dest_city']['id'] ?? null,
                'Название' => $order['dest_city']['name'] ?? null
            ];

            $mapOrder['Статус_заказа'] = $order['status']['name'] ?? null;

            $mapOrder['Забор_груза'] = [
                'Флаг_необходимости_забора' => $order['take_need'] ?? null,
                'Забор_в_пределах_города' => $order['take_in_city'] ?? null,
                'Адрес_забора' => $order['take_address'] ?? null,
                'Дистанция_забора' => $order['take_distance'] ?? null,
                'Точный_забор' => $order['take_point'] ?? null,
                'Просчитанная_цена' => $order['take_price'] ?? null,
                'Название_города_забора' => $order['take_city_name'] ?? null,
            ];

            $mapOrder['Доставка_груза'] = [
                'Флаг_необходимости_забора' => $order['delivery_need'] ?? null,
                'Забор_в_пределах_города' => $order['delivery_in_city'] ?? null,
                'Адрес_забора' => $order['delivery_address'] ?? null,
                'Дистанция_забора' => $order['delivery_distance'] ?? null,
                'Точный_забор' => $order['delivery_point'] ?? null,
                'Просчитанная_цена' => $order['delivery_price'] ?? null,
                'Название_города_забора' => $order['delivery_city_name'] ?? null,
            ];

            $mapOrder['Пакеты'] = array_map(function ($order_item) {
                return [
                    "Идентификатор_на_сайте" => $order_item['id'] ?? null,
                    "Длина" => $order_item['length'] ?? null,
                    "Ширина" => $order_item['width'] ?? null,
                    "Высота" => $order_item['height'] ?? null,
                    "Объём" => $order_item['volume'] ?? null,
                    "Вес" => $order_item['weight'] ?? null,
                    "Количество" => $order_item['quantity'] ?? null,
                ];
            }, $order['order_items']);

            $mapOrder['Отправитель'] = [
                'Тип_контрагента' => $order['sender_type']['name'] ?? null,
                'Правовая_форма' => $order['sender_legal_form'] ?? null,
                'Название_компании' => $order['sender_company_name'] ?? null,
                'Адрес' => [
                    'Город' => $order['sender_legal_address_city'] ?? null,
                    'Улица' => $order['sender_legal_address_street'] ?? null,
                    'Дом' => $order['sender_legal_address_house'] ?? null,
                    'Корпус' => $order['sender_legal_address_block'] ?? null,
                    'Строение' => $order['sender_legal_address_building'] ?? null,
                    'Квартира_офис' => $order['sender_legal_address_apartment'] ?? null,
                ],
                'ИНН' => $order['sender_inn'] ?? null,
                'КПП' => $order['sender_kpp'] ?? null,
                'Имя' => $order['sender_name'] ?? null,
                'Контактное_лицо' => $order['sender_contact_person'] ?? null,
                'Телефон' => $order['sender_phone'] ?? null,
                'Дополнительная_информация' => $order['sender_addition_info'] ?? null,
                'Серия_паспорта' => $order['sender_passport_series'] ?? null,
                'Номер_паспорта' => $order['sender_passport_number'] ?? null,
            ];

            $mapOrder['Получатель'] = [
                'Тип_контрагента' => $order['recipient_type']['name'] ?? null,
                'Правовая_форма' => $order['recipient_legal_form'] ?? null,
                'Название_компании' => $order['recipient_company_name'] ?? null,
                'Адрес' => [
                    'Город' => $order['recipient_legal_address_city'] ?? null,
                    'Улица' => $order['recipient_legal_address_street'] ?? null,
                    'Дом' => $order['recipient_legal_address_house'] ?? null,
                    'Корпус' => $order['recipient_legal_address_block'] ?? null,
                    'Строение' => $order['recipient_legal_address_building'] ?? null,
                    'Квартира_офис' => $order['recipient_legal_address_apartment'] ?? null,
                ],
                'ИНН' => $order['recipient_inn'] ?? null,
                'КПП' => $order['recipient_kpp'] ?? null,
                'Имя' => $order['recipient_name'] ?? null,
                'Контактное_лицо' => $order['recipient_contact_person'] ?? null,
                'Телефон' => $order['recipient_phone'] ?? null,
                'Дополнительная_информация' => $order['recipient_addition_info'] ?? null,
                'Серия_паспорта' => $order['recipient_passport_series'] ?? null,
                'Номер_паспорта' => $order['recipient_passport_number'] ?? null,
            ];

            return $mapOrder;
        }, $orders);

        foreach($orders as $order) {
            $response1c = Api1CHelper::post('create_order', $order);
            if(
                $response1c['status'] == 200 &&
                $response1c['status']['status'] === 'success' &&
                !empty($response1c['status']['id'])
            ) {
                DB::table('orders')->where('id', $order['Идентификатор_на_сайте'])->update([
                    'code_1c' => $response1c['status']['id'],
                    'sync_need' => false
                ]);
                dd($response1c);
            }
        }
        dd('No results');
    }

    public function documentList() {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'document_list',
            [
                "user_id" => "f008aa7f-29d6-11e9-80c7-000d3a396ad2",
                "order_id" => "2ef09a62-8dbb-11e9-a688-001c4208e0b2"
            ]
        );

        dd($response1c);
    }

    public function documentById() {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'document/id',
            [
                "user_id" => "f008aa7f-29d6-11e9-80c7-000d3a396ad2",
                "document_id" => "f22b5b40-3c29-11e9-80f7-000d3a396ad2",
                "type" => 5,
                "empty_fields" => true
            ]
        );

        dd($response1c);
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
}
