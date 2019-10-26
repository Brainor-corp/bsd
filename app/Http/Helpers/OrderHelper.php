<?php

namespace App\Http\Helpers;

class OrderHelper {
    public static function orderToPdfData($order) {
        $documentData = [
            'Представление' => "Заявка на перевозку № $order->id",
            'ДатаИсполнения' => $order->order_date ? $order->order_date->format('d.m.Y') : '',
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
            'РежимРаботыСклада' => '', // todo не запрашивается
            'ГородНазначения' => $order->dest_city_name,
            'Грузополучатель' => '', // Получается ниже
            'ГрузополучательТелефон' => $order->recipient_phone,
            'АдресДоставки' => $order->delivery_address ?? "",
            'КонтактноеЛицоГрузополучателя' => $order->recipient_contact_person ?? "",
            'АдресГрузополучателя' => $order->recipient_legal_address ?? "",
            'Плательщик' => '', // Получается ниже
            'ФормаОплаты' => $order->payment->name ?? "",
            'Заявку_заполнил' => $order->order_creator,
            'ПлательщикEmail' => $order->payer_email
        ];

        if(isset($order->sender_type->name)) {
            if($order->sender_type->slug === 'fizicheskoe-lico') {
                $documentData['Грузоотправитель'] = $order->sender_name;
            } else {
                $documentData['Грузоотправитель'] = $order->sender_company_name;
            }
        }

        if(isset($order->recipient_type->name)) {
            if($order->recipient_type->slug === 'fizicheskoe-lico') {
                $documentData['Грузополучатель'] = $order->recipient_name;
            } else {
                $documentData['Грузополучатель'] = $order->recipient_company_name;
            }
        }

        switch($order->payer->name) {
            case "Отправитель":
                $documentData['Плательщик'] = $mapOrder['Грузоотправитель'] ?? '';
                break;

            case "Получатель":
                $documentData['Плательщик'] = $mapOrder['Грузополучатель'] ?? '';
                break;

            default:
                if($order->payer_form_type->slug === 'fizicheskoe-lico') {
                    $mapOrder['Плательщик'] = $order->payer_name ?? "";
                } else {
                    $mapOrder['Плательщик'] = $order->payer_company_name;
                }
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