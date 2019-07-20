<?php

namespace App\Jobs;

use App\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrdersSyncTo1c implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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
            $totalVolume = 0;
            foreach($order['order_items'] as $order_item) {
                $totalVolume += $order_item['volume'] * $order_item['quantity'];
            }

            $mapOrder = [];

            $mapOrder['Идентификатор_на_сайте'] = intval($order['id']);
            $mapOrder['Название_груза'] = $order['shipping_name'] ?? "";
            $mapOrder['Общий_вес'] = floatval($order['total_weight']);
            $mapOrder['Общий_объем'] = floatval($totalVolume);
            $mapOrder['Время_доставки'] = Carbon::createFromFormat('Y-m-d H:i:s', $order['order_date'])->format('Y-m-d\TH:i:s');
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
            $mapOrder['Способ_оплаты'] = $order['payment']['name'] ?? "";

            if(!empty($order['code_1c'])) {
                $mapOrder['Идентификатор_1С'] = $order['code_1c'];
            }

            $mapOrder['Дата_и_время_создания_заказа'] = Carbon::createFromFormat('Y-m-d H:i:s', $order['created_at'])->format('Y-m-d\TH:i:s');

            if(isset($order['order_finish_date'])) {
                $mapOrder['Дата_и_время_завершения_заказа'] = Carbon::createFromFormat('Y-m-d H:i:s', $order['order_finish_date'])->format('Y-m-d\TH:i:s');
            }

            $mapOrder['Плательщик'] = [
                'Тип_плательщика' => $order['payer']['name'],
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

            switch($order['payer']['name']) {
                case "Отправитель": $mapOrder['Плательщик']['Тип_контрагента'] = $order['sender_type']['name']; break;
                case "Получатель": $mapOrder['Плательщик']['Тип_контрагента'] = $order['recipient_type']['name']; break;
                default: $mapOrder['Плательщик']['Тип_контрагента'] = $order['payer_form_type']['name']; break;
            }

            if(isset($order['order_services'])) {
                $mapOrder['Услуги'] = array_map(function ($order_service) {
                    return [
                        'Идентификатор_на_сайте' => $order_service['id'],
                        'Название' => $order_service['name'] ?? "",
                        'Просчитанная_цена' => is_numeric($order_service['pivot']['price']) ? intval($order_service['pivot']['price']) : 0,
                    ];
                }, $order['order_services']);
            }

            $mapOrder['Город_отправления'] = [
                'Идентификатор_на_сайте' => intval($order['ship_city']['id']),
                'Название' => $order['ship_city']['name'] ?? ""
            ];

            $mapOrder['Город_назначения'] = [
                'Идентификатор_на_сайте' => $order['dest_city']['id'],
                'Название' => $order['dest_city']['name'] ?? ""
            ];

            $mapOrder['Статус_заказа'] = $order['status']['name'] ?? "";

            $mapOrder['Забор_груза'] = [
                'Флаг_необходимости' => !!intval($order['take_need']),
                'Экспедиция_в_пределах_города' => !!intval($order['take_in_city']),
                'Адрес_экспедиции' => $order['take_address'] ?? "",
                'Дистанция_экспедиции' => strval($order['take_distance']),
                'Точная_экспедиция' => !!intval($order['take_point']),
                'Просчитанная_цена' => is_numeric($order['take_price']) ? floatval($order['take_price']) : 0,
                'Название_города_экспедиции' => $order['take_city_name'] ?? "",
            ];

            $mapOrder['Доставка_груза'] = [
                'Флаг_необходимости' => !!intval($order['delivery_need']),
                'Экспедиция_в_пределах_города' => !!intval($order['delivery_in_city']),
                'Адрес_экспедиции' => $order['delivery_address'] ?? "",
                'Дистанция_экспедиции' => strval($order['delivery_distance']),
                'Точная_экспедиция' => !!intval($order['delivery_point']),
                'Просчитанная_цена' => is_numeric($order['delivery_price']) ? intval($order['delivery_price']) : 0,
                'Название_города_экспедиции' => $order['delivery_city_name'] ?? "",
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
                'Дополнительная_информация' => $order['sender_addition_info'] ?? "",
                'Серия_паспорта' => strlen($order['sender_passport_series']) >= 0 && strlen($order['sender_passport_series']) <= 4 ? strval($order['sender_passport_series']) : "",
                'Номер_паспорта' => strlen($order['sender_passport_number']) >= 0 && strlen($order['sender_passport_number']) <= 6 ? strval($order['sender_passport_number']) : "",
            ];

            if(isset($order['sender_type']['name'])) {
                $mapOrder['Отправитель']['Тип_контрагента'] = $order['sender_type']['name'];
            }

            $mapOrder['Получатель'] = [
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
                'Дополнительная_информация' => $order['recipient_addition_info'] ?? "",
                'Серия_паспорта' => strlen($order['recipient_passport_series']) >= 0 && strlen($order['recipient_passport_series']) <= 4 ? strval($order['recipient_passport_series']) : "",
                'Номер_паспорта' => strlen($order['recipient_passport_number']) >= 0 && strlen($order['recipient_passport_number']) <= 6 ? strval($order['recipient_passport_number']) : "",
            ];

            if(isset($order['recipient_type']['name'])) {
                $mapOrder['Получатель']['Тип_контрагента'] = $order['recipient_type']['name'];
            }

            return $mapOrder;
        }, $orders);

        foreach($orders as $order) {
            dispatch(new OrderSyncTo1c($order));
        }
    }
}
