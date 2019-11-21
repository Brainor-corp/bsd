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
            'Вес' => $order->total_weight,
            'Объем' => $order->total_volume,
            'КоличествоМест' => array_sum(array_column($order->order_items->toArray(), 'quantity')),
            'МягкаяУпаковка' => !empty($order->order_services->where('slug', 'myagkaya-upakovka')->first()) ? 'Да' : 'Нет',
            'ЖесткаяУпаковка' => !empty($order->order_services->where('slug', 'obreshetka')->first()) ? 'Да' : 'Нет',
            'Паллетирование' => !empty($order->order_services->where('slug', 'palletirovanie')->first()) ? 'Да' : 'Нет',
            'Сумма_страховки' => $order->insurance,
            'ГородОтправления' => $order->ship_city_name,
            'ОрганизацияОтправления' => '', // todo не запрашивается
            'ГрузоотправительТелефон' => $order->sender_phone,
            'АдресЗабора' => $order->take_address ?? "",
            'РежимРаботыСклада' => 'с 9:00 до 18:00',
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
}
