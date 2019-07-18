<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Api1CHelper;
use App\Order;
use Carbon\Carbon;
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
            )
            ->orderBy('created_at', 'desc')
            ->where('id', 98)
            ->limit(1)

            ->get()->toArray();

        // Преобразуем данные модели в вид, необходимый для отправки в 1с
        $orders = array_map(function ($order) {
            $totalVolume = 0;
            foreach($order['order_items'] as $order_item) {
                $totalVolume += $order_item['volume'] * $order_item['quantity'];
            }

            $mapOrder = [];

            $mapOrder['Идентификатор_на_сайте'] = intval($order['id']);
            $mapOrder['Название_груза'] = $order['shipping_name'];
            $mapOrder['Общий_вес'] = floatval($order['total_weight']);
            $mapOrder['Общий_объем'] = floatval($totalVolume);
            $mapOrder['Время_доставки'] = Carbon::createFromFormat('Y-m-d H:i:s', $order['order_date'])->format('Y-m-d\Th:i:s');
            $mapOrder['Количество_мест'] = count($order['order_items']);
            $mapOrder['Итоговая_цена'] = is_numeric($order['total_price']) ? intval($order['total_price']) : 0;
            $mapOrder['Базовая_цена_маршрута'] = is_numeric($order['base_price']) ? intval($order['base_price']) : 0;

            $mapOrder['Страховка'] = [
                'Сумма_страховки' => is_numeric($order['insurance']) ? intval($order['insurance']) : 0,
                'Цена_страхования' => is_numeric($order['insurance_amount']) ? intval($order['insurance_amount']) : 0,
            ];

            $mapOrder['Скидка'] = [
                'Процент_скидки' => is_numeric($order['discount']) ? intval($order['discount']) : 0,
                'Сумма_скидки' => is_numeric($order['discount_amount']) ? intval($order['discount_amount']) : 0,
            ];

            $mapOrder['Идентификатор_пользователя_на_сайте'] = intval($order['user_id']);
            $mapOrder['Способ_оплаты'] = $order['payment']['name'];

            if(!empty($order['code_1c'])) {
                $mapOrder['Идентификатор_1С'] = $order['code_1c'];
            }

            $mapOrder['Дата_и_время_создания_заказа'] = Carbon::createFromFormat('Y-m-d h:i:s', $order['created_at'])->format('Y-m-d\Th:i:s');

            if(isset($order['order_finish_date'])) {
                $mapOrder['Дата_и_время_завершения_заказа'] = Carbon::createFromFormat('Y-m-d', $order['order_finish_date'])->format('Y-m-d\Th:i:s');
            }

            $mapOrder['Плательщик'] = [
                'Тип_плательщика' => $order['payer']['name'],
                'Тип_контрагента' => $order['payer_form_type']['name'],
                'Правовая_форма' => $order['payer_legal_form'] ?? "",
                'Наименование' => strlen($order['payer_company_name']) >= 3 ? $order['payer_company_name'] : "---",
                'Адрес' => [
                    'Город' => $order['payer_legal_address_city'] ?? "",
                    'Улица' => $order['payer_legal_address_street'] ?? "",
                    'Дом' => $order['payer_legal_address_house'] ?? "",
                    'Корпус' => $order['payer_legal_address_block'] ?? "",
                    'Строение' => $order['payer_legal_address_building'] ?? "",
                    'Квартира_офис' => $order['payer_legal_address_apartment'] ?? "",
                ],
                'ИНН' => strlen($order['payer_inn']) >= 0 && strlen($order['payer_inn']) <= 12 ? strval($order['payer_inn']) : "",
                'КПП' => strlen($order['payer_kpp']) >= 0 && strlen($order['payer_kpp']) <= 9 ? strval($order['payer_kpp']) : "",
                'Имя' => $order['payer_name'] ?? "",
                'Контактное_лицо' => $order['payer_contact_person'] ?? "",
                'Телефон' => strlen($order['payer_phone']) >= 0 && strlen($order['payer_phone']) <= 11 ? strval($order['payer_phone']) : "",
                'Дополнительная_информация' => $order['payer_addition_info'] ?? "",
                'Серия_паспорта' => strlen($order['payer_passport_series']) >= 0 && strlen($order['payer_passport_series']) <= 4 ? strval($order['payer_passport_series']) : "",
                'Номер_паспорта' => strlen($order['payer_passport_number']) >= 0 && strlen($order['payer_passport_number']) <= 6 ? strval($order['payer_passport_number']) : "",
            ];

            if(isset($order['order_services'])) {
                $mapOrder['Услуги'] = array_map(function ($order_service) {
                    return [
                        'Идентификатор_на_сайте' => $order_service['id'],
                        'Название' => $order_service['name'],
                        'Просчитанная_цена' => is_numeric($order_service['pivot']['price']) ? intval($order_service['pivot']['price']) : 0,
                    ];
                }, $order['order_services']);
            }

            $mapOrder['Город_отправления'] = [
                'Идентификатор_на_сайте' => intval($order['ship_city']['id']),
                'Название' => $order['ship_city']['name']
            ];

            $mapOrder['Город_назначения'] = [
                'Идентификатор_на_сайте' => $order['dest_city']['id'],
                'Название' => $order['dest_city']['name']
            ];

            $mapOrder['Статус_заказа'] = $order['status']['name'];

            $mapOrder['Забор_груза'] = [
                'Флаг_необходимости' => !!intval($order['take_need']),
                'Экспедиция_в_пределах_города' => !!intval($order['take_in_city']),
                'Адрес_экспедиции' => $order['take_address'] ?? "",
                'Дистанция_экспедиции' => strval($order['take_distance']),
                'Точная_экспедиция' => !!intval($order['take_point']),
                'Просчитанная_цена' => is_numeric($order['take_price']) ? floatval($order['take_price']) : 0,
                'Название_города_экспедиции' => $order['take_city_name'],
            ];

            $mapOrder['Доставка_груза'] = [
                'Флаг_необходимости' => !!intval($order['delivery_need']),
                'Экспедиция_в_пределах_города' => !!intval($order['delivery_in_city']),
                'Адрес_экспедиции' => $order['delivery_address'] ?? "",
                'Дистанция_экспедиции' => strval($order['delivery_distance']),
                'Точная_экспедиция' => !!intval($order['delivery_point']),
                'Просчитанная_цена' => is_numeric($order['delivery_price']) ? intval($order['delivery_price']) : 0,
                'Название_города_экспедиции' => $order['delivery_city_name'],
            ];

            $mapOrder['Пакеты'] = array_map(function ($order_item) {
                return [
                    "Идентификатор_на_сайте" => $order_item['id'],
                    "Длина" => floatval($order_item['length']),
                    "Ширина" => floatval($order_item['width']),
                    "Высота" => floatval($order_item['height']),
                    "Объем" => floatval($order_item['volume']),
                    "Вес" => floatval($order_item['weight']),
                    "Количество" => intval($order_item['quantity']),
                ];
            }, $order['order_items']);

            $mapOrder['Отправитель'] = [
                'Тип_контрагента' => $order['sender_type']['name'],
                'Правовая_форма' => $order['sender_legal_form'] ?? "",
                'Наименование' => strlen($order['sender_company_name']) >= 3 ? $order['sender_company_name'] : "---",
                'Адрес' => [
                    'Город' => $order['sender_legal_address_city'] ?? "",
                    'Улица' => $order['sender_legal_address_street'] ?? "",
                    'Дом' => $order['sender_legal_address_house'] ?? "",
                    'Корпус' => $order['sender_legal_address_block'] ?? "",
                    'Строение' => $order['sender_legal_address_building'] ?? "",
                    'Квартира_офис' => $order['sender_legal_address_apartment'] ?? "",
                ],
                'ИНН' => strlen($order['sender_inn']) >= 0 && strlen($order['sender_inn']) <= 12 ? strval($order['sender_inn']) : "",
                'КПП' => strlen($order['sender_kpp']) >= 0 && strlen($order['sender_kpp']) <= 9 ? strval($order['sender_kpp']) : "",
                'Имя' => $order['sender_name'] ?? "",
                'Контактное_лицо' => $order['sender_contact_person'] ?? "",
                'Телефон' => strlen($order['sender_phone']) >= 0 && strlen($order['sender_phone']) <= 11 ? strval($order['sender_phone']) : "",
                'Дополнительная_информация' => $order['sender_addition_info'],
                'Серия_паспорта' => strlen($order['sender_passport_series']) >= 0 && strlen($order['sender_passport_series']) <= 4 ? strval($order['sender_passport_series']) : "",
                'Номер_паспорта' => strlen($order['sender_passport_number']) >= 0 && strlen($order['sender_passport_number']) <= 6 ? strval($order['sender_passport_number']) : "",
            ];

            $mapOrder['Получатель'] = [
                'Тип_контрагента' => $order['recipient_type']['name'],
                'Правовая_форма' => $order['recipient_legal_form'] ?? "",
                'Наименование' => strlen($order['recipient_company_name']) >= 3 ? $order['recipient_company_name'] : "---",
                'Адрес' => [
                    'Город' => $order['recipient_legal_address_city'] ?? "",
                    'Улица' => $order['recipient_legal_address_street'] ?? "",
                    'Дом' => $order['recipient_legal_address_house'] ?? "",
                    'Корпус' => $order['recipient_legal_address_block'] ?? "",
                    'Строение' => $order['recipient_legal_address_building'] ?? "",
                    'Квартира_офис' => $order['recipient_legal_address_apartment'] ?? "",
                ],
                'ИНН' => strlen($order['recipient_inn']) >= 0 && strlen($order['recipient_inn']) <= 12 ? strval($order['recipient_inn']) : "",
                'КПП' => strlen($order['recipient_kpp']) >= 0 && strlen($order['recipient_kpp']) <= 9 ? strval($order['recipient_kpp']) : "",
                'Имя' => $order['recipient_name'] ?? "",
                'Контактное_лицо' => $order['recipient_contact_person'] ?? "",
                'Телефон' => strlen($order['recipient_phone']) >= 0 && strlen($order['recipient_phone']) <= 11 ? strval($order['recipient_phone']) : "",
                'Дополнительная_информация' => $order['recipient_addition_info'],
                'Серия_паспорта' => strlen($order['recipient_passport_series']) >= 0 && strlen($order['recipient_passport_series']) <= 4 ? strval($order['recipient_passport_series']) : "",
                'Номер_паспорта' => strlen($order['recipient_passport_number']) >= 0 && strlen($order['recipient_passport_number']) <= 6 ? strval($order['recipient_passport_number']) : "",
            ];

            return $mapOrder;
        }, $orders);

        foreach($orders as $order) {
            $response1c = Api1CHelper::post('create_order', $order);
            dd([
                'send' => json_encode($order),
                'response' => $response1c
            ]);


            if(
                $response1c['status'] == 200 &&
                $response1c['status']['status'] === 'success' &&
                !empty($response1c['status']['id'])
            ) {
                DB::table('orders')->where('id', $order['Идентификатор_на_сайте'])->update([
                    'code_1c' => $response1c['status']['id'],
                    'sync_need' => false
                ]);
            }
        }
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
