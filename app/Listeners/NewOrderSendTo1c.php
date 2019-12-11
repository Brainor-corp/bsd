<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\OrderSyncTo1c;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrderSendTo1c
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OrderCreated $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        $sendOrder = [];

        $sendOrder['Идентификатор_на_сайте'] = intval($order->id);
        $sendOrder['Название_груза'] = $order->shipping_name ?? "";
        $sendOrder['Общий_вес'] = floatval($order->total_weight);
        $sendOrder['Общий_объем'] = floatval($order->total_volume);
        $sendOrder['Примечания'] = $order->cargo_comment ?? '';

        if(isset($order->order_date) && isset($order->ship_time_from) && isset($order->ship_time_to)) {
            $sendOrder['Время_доставки'] = [
                'День' => Carbon::createFromFormat('Y-m-d H:i:s', $order->order_date)->format('Y-m-d'),
                'Время_с' => Carbon::createFromFormat('H:i:s', $order->ship_time_from)->format('H:i:s'),
                'Время_по' => Carbon::createFromFormat('H:i:s', $order->ship_time_to)->format('H:i:s')
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

        dispatch(new OrderSyncTo1c($sendOrder));
    }
}
