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
            ->limit(1)
            ->get()->toArray();

        // Преобразуем данные модели в вид, необходимый для отправки в 1с
        $orders = array_map(function ($order) {
            $mapOrder = [];

            $mapOrder['Идентификатор_на_сайте'] = $order['id'];
            $mapOrder['Название_груза'] = $order['shipping_name'];
            $mapOrder['Общий_вес'] = floatval($order['total_weight']);
            $mapOrder['Время_доставки'] = Carbon::createFromFormat('Y-m-d H:i:s', $order['order_date'])->format('Y-m-d');
            $mapOrder['Итоговая_цена'] = is_numeric($order['total_price']) ? floatval($order['total_price']) : 0;
            $mapOrder['Базовая_цена_маршрута'] = is_numeric($order['base_price']) ? floatval($order['base_price']) : 0;

            $mapOrder['Страховка'] = [
                'Сумма_страховки' => is_numeric($order['insurance']) ? floatval($order['insurance']) : 0,
                'Цена_страхования' => is_numeric($order['insurance_amount']) ? floatval($order['insurance_amount']) : 0,
            ];

            $mapOrder['Скидка'] = [
                'Процент_скидки' => is_numeric($order['discount']) ? intval($order['discount']) : 0,
                'Сумма_скидки' => is_numeric($order['discount_amount']) ? floatval($order['discount_amount']) : 0,
            ];

            $mapOrder['Идентификатор_пользователя_на_сайте'] = $order['user_id'];
            $mapOrder['Способ_оплаты'] = $order['payment']['name'];
            $mapOrder['Идентификатор_1С'] = $order['code_1c'];
            $mapOrder['Дата_и_время_создания_заказа'] = $order['created_at'];
            $mapOrder['Дата_и_время_завершения_заказа'] = $order['order_finish_date'];

            $mapOrder['Плательщик'] = [
                'Тип_плательщика' => $order['payer']['name'],
                'Тип_контрагента' => $order['payer_form_type']['name'],
                'Правовая_форма' => $order['payer_legal_form'],
                'Название_компании' => $order['payer_company_name'],
                'Адрес' => [
                    'Город' => $order['payer_legal_address_city'],
                    'Улица' => $order['payer_legal_address_street'],
                    'Дом' => $order['payer_legal_address_house'],
                    'Корпус' => $order['payer_legal_address_block'],
                    'Строение' => $order['payer_legal_address_building'],
                    'Квартира_офис' => $order['payer_legal_address_apartment'],
                ],
                'ИНН' => strval($order['payer_inn']),
                'КПП' => intval($order['payer_kpp']) >= 10000000 && intval($order['payer_kpp']) <= 99999999 ? intval($order['payer_kpp']) : null,
                'Имя' => $order['payer_name'],
                'Контактное_лицо' => $order['payer_contact_person'],
                'Телефон' => intval($order['payer_phone']) >= 70000000000 && intval($order['payer_phone']) <= 89999999999 ? intval($order['payer_phone']) : null,
                'Дополнительная_информация' => $order['payer_addition_info'],
                'Серия_паспорта' => intval($order['payer_passport_series']) >= 1000 && intval($order['payer_passport_series']) <= 999999 ? intval($order['payer_passport_series']) : null,
                'Номер_паспорта' => intval($order['payer_passport_number']) >= 1000 && intval($order['payer_passport_number']) <= 999999 ? intval($order['payer_passport_number']) : null,
            ];

            $mapOrder['Услуги'] = array_map(function ($order_service) {
                return [
                    'Идентификатор_на_сайте' => $order_service['id'],
                    'Название' => $order_service['name'],
                    'Просчитанная_цена' => is_numeric($order_service['pivot']['price']) ? floatval($order_service['pivot']['price']) : 0,
                ];
            }, $order['order_services']);

            $mapOrder['Услуги'] = count($mapOrder['Услуги']) ? $mapOrder['Услуги'] : null;

            $mapOrder['Город_отправления'] = [
                'Идентификатор_на_сайте' => $order['ship_city']['id'],
                'Название' => $order['ship_city']['name']
            ];

            $mapOrder['Город_назначения'] = [
                'Идентификатор_на_сайте' => $order['dest_city']['id'],
                'Название' => $order['dest_city']['name']
            ];

            $mapOrder['Статус_заказа'] = $order['status']['name'];

            $mapOrder['Забор_груза'] = [
                'Флаг_необходимости_забора' => $order['take_need'],
                'Забор_в_пределах_города' => $order['take_in_city'],
                'Адрес_забора' => $order['take_address'],
                'Дистанция_забора' => strval($order['take_distance']),
                'Точный_забор' => $order['take_point'],
                'Просчитанная_цена' => is_numeric($order['take_price']) ? floatval($order['take_price']) : 0,
                'Название_города_забора' => $order['take_city_name'],
            ];

            $mapOrder['Доставка_груза'] = [
                'Флаг_необходимости_забора' => $order['delivery_need'],
                'Забор_в_пределах_города' => $order['delivery_in_city'],
                'Адрес_забора' => $order['delivery_address'],
                'Дистанция_забора' => $order['delivery_distance'],
                'Точный_забор' => $order['delivery_point'],
                'Просчитанная_цена' => is_numeric($order['delivery_price']) ? floatval($order['delivery_price']) : 0,
                'Название_города_забора' => $order['delivery_city_name'],
            ];

            $mapOrder['Пакеты'] = array_map(function ($order_item) {
                return [
                    "Идентификатор_на_сайте" => $order_item['id'],
                    "Длина" => $order_item['length'],
                    "Ширина" => $order_item['width'],
                    "Высота" => $order_item['height'],
                    "Объём" => $order_item['volume'],
                    "Вес" => $order_item['weight'],
                    "Количество" => $order_item['quantity'],
                ];
            }, $order['order_items']);

            $mapOrder['Отправитель'] = [
                'Тип_контрагента' => $order['sender_type']['name'],
                'Правовая_форма' => $order['sender_legal_form'],
                'Название_компании' => $order['sender_company_name'],
                'Адрес' => [
                    'Город' => $order['sender_legal_address_city'],
                    'Улица' => $order['sender_legal_address_street'],
                    'Дом' => $order['sender_legal_address_house'],
                    'Корпус' => $order['sender_legal_address_block'],
                    'Строение' => $order['sender_legal_address_building'],
                    'Квартира_офис' => $order['sender_legal_address_apartment'],
                ],
                'ИНН' => strval($order['sender_inn']),
                'КПП' => intval($order['sender_kpp']) >= 10000000 && intval($order['sender_kpp']) <= 99999999 ? intval($order['sender_kpp']) : null,
                'Имя' => $order['sender_name'],
                'Контактное_лицо' => $order['sender_contact_person'],
                'Телефон' => intval($order['sender_phone']) >= 70000000000 && intval($order['sender_phone']) <= 89999999999 ? intval($order['sender_phone']) : null,
                'Дополнительная_информация' => $order['sender_addition_info'],
                'Серия_паспорта' => intval($order['sender_passport_series']) >= 1000 && intval($order['sender_passport_series']) <= 999999 ? intval($order['sender_passport_series']) : null,
                'Номер_паспорта' => intval($order['sender_passport_number']) >= 1000 && intval($order['sender_passport_number']) <= 999999 ? intval($order['sender_passport_number']) : null,
            ];

            $mapOrder['Получатель'] = [
                'Тип_контрагента' => $order['recipient_type']['name'],
                'Правовая_форма' => $order['recipient_legal_form'],
                'Название_компании' => $order['recipient_company_name'],
                'Адрес' => [
                    'Город' => $order['recipient_legal_address_city'],
                    'Улица' => $order['recipient_legal_address_street'],
                    'Дом' => $order['recipient_legal_address_house'],
                    'Корпус' => $order['recipient_legal_address_block'],
                    'Строение' => $order['recipient_legal_address_building'],
                    'Квартира_офис' => $order['recipient_legal_address_apartment'],
                ],
                'ИНН' => strval($order['recipient_inn']),
                'КПП' => intval($order['recipient_kpp']) >= 10000000 && intval($order['recipient_kpp']) <= 99999999 ? intval($order['recipient_kpp']) : null,
                'Имя' => $order['recipient_name'],
                'Контактное_лицо' => $order['recipient_contact_person'],
                'Телефон' => intval($order['recipient_phone']) >= 70000000000 && intval($order['recipient_phone']) <= 89999999999 ? intval($order['recipient_phone']) : null,
                'Дополнительная_информация' => $order['recipient_addition_info'],
                'Серия_паспорта' => intval($order['recipient_passport_series']) >= 1000 && intval($order['recipient_passport_series']) <= 999999 ? intval($order['recipient_passport_series']) : null,
                'Номер_паспорта' => intval($order['recipient_passport_number']) >= 1000 && intval($order['recipient_passport_number']) <= 999999 ? intval($order['recipient_passport_number']) : null,
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
