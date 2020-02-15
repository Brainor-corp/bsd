<?php

namespace App\Http\Helpers;

use Carbon\Carbon;

class OrderHelper {
    public static function orderToPdfData($order) {
        $documentData = [
            'Представление' => "Заявка на перевозку № $order->id",
            'ДатаИсполнения' => $order->order_date ? $order->order_date->format('d.m.Y') : '',
            'ДатаИсполненияС' => $order->ship_time_from ? Carbon::createFromFormat('H:i:s', $order->ship_time_from)->format('H:i') : '',
            'ДатаИсполненияПо' => $order->ship_time_to ? Carbon::createFromFormat('H:i:s', $order->ship_time_to)->format('H:i') : '',
            'Груз' => $order->shipping_name,
            'Вес' => $order->total_weight ?? $order->total_weight,
            'Объем' => $order->actual_volume ?? $order->total_volume,
            'КоличествоМест' => $order->total_quantity ?? array_sum(array_column($order->order_items->toArray(), 'quantity')),
            'МягкаяУпаковка' => !empty($order->order_services->where('slug', 'myagkaya-upakovka')->first()) ? 'Да' : 'Нет',
            'ЖесткаяУпаковка' => !empty($order->order_services->where('slug', 'obreshetka')->first()) ? 'Да' : 'Нет',
            'Паллетирование' => !empty($order->order_services->where('slug', 'palletirovanie')->first()) ? 'Да' : 'Нет',
            'Сумма_страховки' => $order->insurance,
            'ГородОтправления' => $order->ship_city_name,
            'ОрганизацияОтправления' => '', // todo не запрашивается
            'ГрузоотправительТелефон' => $order->sender_phone,
            'АдресЗабора' => $order->take_address ?? "",
            'РежимРаботыСклада' => $order->warehouse_schedule ?? 'с 9:00 до 18:00',
            'ГородНазначения' => $order->dest_city_name,
            'ГрузополучательТелефон' => $order->recipient_phone,
            'АдресДоставки' => $order->delivery_address ?? "",
            'КонтактноеЛицоГрузополучателя' => $order->recipient_contact_person ?? "",
            'КонтактноеЛицоГрузоотправителя' => $order->sender_contact_person ?? "",
            'КонтактноеЛицоПлательщика' => '', // Получается ниже
            'АдресГрузополучателя' => $order->recipient_legal_address ?? "",
            'Плательщик' => '', // Получается ниже
            'ФормаОплаты' => $order->payment->name ?? "",
            'Заявку_заполнил' => $order->order_creator,
            'ПлательщикEmail' => $order->payer_email,
            'Комментарий' => $order->cargo_comment,
        ];

        if(isset($order->sender_type->name)) {
            if($order->sender_type->slug === 'fizicheskoe-lico') {
                $documentData['Грузоотправитель'] = $order->sender_name;
            } else {
                $documentData['Грузоотправитель'] = "$order->sender_legal_form \"$order->sender_company_name\"";
                $documentData['ГрузоотправительИНН'] = $order->sender_inn;
                $documentData['ГрузоотправительКПП'] = $order->sender_kpp;
            }
        }

        if(isset($order->recipient_type->name)) {
            if($order->recipient_type->slug === 'fizicheskoe-lico') {
                $documentData['Грузополучатель'] = $order->recipient_name;
            } else {
                $documentData['Грузополучатель'] = "$order->recipient_legal_form \"$order->recipient_company_name\"";
                $documentData['ГрузополучательИНН'] = $order->recipient_inn;
                $documentData['ГрузополучательКПП'] = $order->recipient_kpp;
            }
        }

        if(isset($order->payer)) {
            switch($order->payer->name) {
                case "Отправитель":
                    $documentData['Плательщик'] = $documentData['Грузоотправитель'] ?? '';
                    $documentData['ПлательщикИНН'] = $documentData['ГрузоотправительИНН'] ?? '';
                    $documentData['ПлательщикКПП'] = $documentData['ГрузоотправительКПП'] ?? '';
                    $documentData['ПлательщикТелефон'] = $documentData['ГрузоотправительТелефон'] ?? '';
                    $documentData['КонтактноеЛицоПлательщика'] = $documentData['КонтактноеЛицоГрузоотправителя'] ?? '';
                    break;

                case "Получатель":
                    $documentData['Плательщик'] = $documentData['Грузополучатель'] ?? '';
                    $documentData['ПлательщикИНН'] = $documentData['ГрузополучательИНН'] ?? '';
                    $documentData['ПлательщикКПП'] = $documentData['ГрузополучательКПП'] ?? '';
                    $documentData['ПлательщикТелефон'] = $documentData['ГрузополучательТелефон'] ?? '';
                    $documentData['КонтактноеЛицоПлательщика'] = $documentData['КонтактноеЛицоГрузополучателя'] ?? '';
                    break;

                default:
                    if($order->payer_form_type->slug === 'fizicheskoe-lico') {
                        $documentData['Плательщик'] = $order->payer_name ?? "";
                    } else {
                        $documentData['Плательщик'] = "$order->payer_legal_form \"$order->payer_company_name\"";
                        $documentData['ПлательщикИНН'] = $order->payer_inn;
                        $documentData['ПлательщикКПП'] = $order->payer_kpp;
                    }
                    $documentData['ПлательщикТелефон'] = $order->payer_phone;
                    $documentData['КонтактноеЛицоПлательщика'] = $order->payer_contact_person;
                    break;
            }
        } else {
            $documentData['Плательщик'] = '';
            $documentData['ПлательщикИНН'] = '';
            $documentData['ПлательщикКПП'] = '';
            $documentData['ПлательщикТелефон'] = '';
            $documentData['КонтактноеЛицоПлательщика'] = '';
        }

        foreach($order->order_items as $package) {
            $documentData['Места'][] = [
                'Длина' => $package->length,
                'Ширина' => $package->width,
                'Высота' => $package->height,
                'Объем' => $package->volume,
                'Вес' => $package->weight,
                'Количество' => $package->quantity,
            ];
        }

        return $documentData;
    }

    public static function orderTo1cFormat($order)
    {
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

        $sendOrder['Количество_мест'] = $order->total_quantity ?? array_sum(array_column($order->order_items->toArray(), 'quantity'));
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

        $sendOrder['РежимРаботыСклада'] = $order->warehouse_schedule ?? 'с 9:00 до 18:00';

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

        if(isset($order->payer)) {
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
                    $sendOrder['Плательщик'] = [
                        'Тип_контрагента' => $order->payer_form_type->name ?? '',
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
        }

        $sendOrder['Плательщик']['Email_плательщика'] = $order->payer_email ?? '';
        $sendOrder['Плательщик']['Тип_плательщика'] = $order->payer->name ?? '';

        return $sendOrder;
    }

    public static function getCounterpartyForms()
    {
        return [
            'ООО',
            'ПАО',
            'ЗАО',
            'АО',
            'ИП',
        ];
    }
}
