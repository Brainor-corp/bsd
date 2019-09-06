<!DOCTYPE html>
<html>

<head>
    <style>
        table th {
            text-align: left;
        }
    </style>
</head>

<body>

<h1>Новая заявка</h1>
<table>
    <tbody>
    <tr>
        <th>Наименование груза</th>
        <td>{{ $order->shipping_name }}</td>
    </tr>
    <tr>
        <th>Габариты (м)</th>
        <td>
            @php($volume = 0)
            @foreach($order->order_items as $order_item)
                @php($volume += $order_item->volume)
                {{
                    "
                        Длина: $order_item->length,
                        Ширина: $order_item->width,
                        Высота: $order_item->height,
                        Объём: $order_item->volume,
                        Кол-во: $order_item->quantity
                    "
                }}
                <br>
            @endforeach
        </td>
    </tr>
    <tr>
        <th>Вес груза (кг)</th>
        <td>{{ $order->total_weight }}</td>
    </tr>
    <tr>
        <th>Объем (м3)</th>
        <td>{{ $volume }}</td>
    </tr>
    <tr>
        <th>Откуда</th>
        <td>
            {{ $order->ship_city_name ?? ($order->ship_city->name ?? '-') }}.
            @if($order->take_need)
                Нужно забрать груз.
                @if($order->take_in_city)
                    В пределах города отправления.
                @else
                    Из: {{ $order->take_address }}.
                @endif

                @if($order->take_point)
                    Забор груза необходимо произвести в гипермаркете, распределительном центре или в точное время (временно́е "окно" менее 1 часа).
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <th>Куда</th>
        <td>
            {{ $order->dest_city_name ?? ($order->dest_city->name ?? '-') }}.
            @if($order->delivery_need)
                Нужно доставить груз.
                @if($order->delivery_in_city)
                    В пределах города назначения.
                @else
                    В: {{ $order->delivery_address }}.
                @endif

                @if($order->delivery_point)
                    Доставку груза необходимо произвести в гипермаркет, распределительный центр или в точное время (временно́е "окно" менее 1 часа).
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <th>Дополнительные услуги</th>
        <td>
            @foreach($order->order_services as $service)
                {{ $service->name }} <br>
            @endforeach
        </td>
    </tr>
    <tr>
        <th>Сумма страховки</th>
        <td>{{ $order->insurance }}</td>
    </tr>
    <tr>
        <th>Скидка (%)</th>
        <td>{{ $order->discount }}</td>
    </tr>
    <tr>
        <th>Отправитель</th>
        <td>
            <strong>Тип:</strong> {{ $order->sender_type->name ?? '-' }}. <br>

            @if(isset($order->sender_type) && $order->sender_type->slug == 'yuridicheskoe-lico')
                <strong>Правовая форма:</strong> {{ $order->sender_legal_form ?? '-' }} <br>
                <strong>Название организации:</strong> {{ $order->sender_company_name ?? '-' }} <br>
                <strong>Юридический адрес:</strong>
                Город - {{ $order->sender_legal_address_city ?? '-' }},
                Адрес - {{ $order->sender_legal_address ?? '-' }} <br>

                <strong>ИНН:</strong> {{ $order->sender_inn ?? '-' }} <br>
                <strong>КПП:</strong> {{ $order->sender_kpp ?? '-' }} <br>
                <strong>Контактное лицо:</strong> {{ $order->sender_contact_person ?? '-' }} <br>
                <strong>Телефон:</strong> {{ $order->sender_phone ?? '-' }} <br>
                <strong>Дополнительная информация:</strong> {{ $order->sender_addition_info ?? '-' }}
            @endif

            @if(isset($order->sender_type) && $order->sender_type->slug == 'fizicheskoe-lico')
                <strong>ФИО:</strong> {{ $order->sender_name ?? '-' }} <br>
                <strong>Паспорт:</strong> {{ $order->sender_passport_series ?? '-' }} {{ $order->sender_passport_number ?? '-' }} <br>
                <strong>Контактное лицо:</strong> {{ $order->sender_contact_person ?? '-' }} <br>
                <strong>Телефон:</strong> {{ $order->sender_phone ?? '-' }} <br>
                <strong>Дополнительная информация:</strong> {{ $order->sender_addition_info ?? '-' }}
            @endif
        </td>
    </tr>
    <tr>
        <th>Получатель</th>
        <td>
            <strong>Тип:</strong> {{ $order->recipient_type->name ?? '-' }}. <br>

            @if(isset($order->recipient_type) && $order->recipient_type->slug == 'yuridicheskoe-lico')
                <strong>Правовая форма:</strong> {{ $order->recipient_legal_form ?? '-' }} <br>
                <strong>Название организации:</strong> {{ $order->recipient_company_name ?? '-' }} <br>
                <strong>Юридический адрес:</strong>
                Город - {{ $order->recipient_legal_address_city ?? '-' }},
                Адрес - {{ $order->recipient_legal_address ?? '-' }} <br>

                <strong>ИНН:</strong> {{ $order->recipient_inn ?? '-' }} <br>
                <strong>КПП:</strong> {{ $order->recipient_kpp ?? '-' }} <br>
                <strong>Контактное лицо:</strong> {{ $order->recipient_contact_person ?? '-' }} <br>
                <strong>Телефон:</strong> {{ $order->recipient_phone ?? '-' }} <br>
                <strong>Дополнительная информация:</strong> {{ $order->recipient_addition_info ?? '-' }}
            @endif

            @if(isset($order->recipient_type) && $order->recipient_type->slug == 'fizicheskoe-lico')
                <strong>ФИО:</strong> {{ $order->recipient_name ?? '-' }} <br>
                <strong>Паспорт:</strong> {{ $order->recipient_passport_series ?? '-' }} {{ $order->recipient_passport_number ?? '-' }} <br>
                <strong>Контактное лицо:</strong> {{ $order->recipient_contact_person ?? '-' }} <br>
                <strong>Телефон:</strong> {{ $order->recipient_phone ?? '-' }} <br>
                <strong>Дополнительная информация:</strong> {{ $order->recipient_addition_info ?? '-' }}
            @endif
        </td>
    </tr>
    <tr>
        <th>Плательщик</th>
        <td>
            <strong>Email:</strong> {{ $order->payer_email ?? '-' }}
            @if(isset($order->payer) && $order->payer->slug === 'otpravitel') <strong>Отправитель</strong> @endif
            @if(isset($order->payer) && $order->payer->slug === 'poluchatel') <strong>Получатель</strong> @endif
            @if(isset($order->payer) && $order->payer->slug === '3-e-lico') <strong>3-е лицо</strong> @endif

            {{--@if(isset($order->payer) && $order->payer->slug === '3-e-lico')--}}
                {{--<strong>Тип:</strong> {{ $order->payer_type->name ?? '-' }}. <br>--}}
                {{--@if(isset($order->payer_type) && $order->payer_type->slug == 'yuridicheskoe-lico')--}}
                    {{--<strong>Правовая форма:</strong> {{ $order->payer_legal_form ?? '-' }} <br>--}}
                    {{--<strong>Название организации:</strong> {{ $order->payer_company_name ?? '-' }} <br>--}}
                    {{--<strong>Юридический адрес:</strong>--}}
                    {{--Город - {{ $order->payer_legal_address_city ?? '-' }},--}}
                    {{--Адрес - {{ $order->payer_legal_address ?? '-' }} <br>--}}

                    {{--<strong>ИНН:</strong> {{ $order->payer_inn ?? '-' }} <br>--}}
                    {{--<strong>КПП:</strong> {{ $order->payer_kpp ?? '-' }} <br>--}}
                    {{--<strong>Контактное лицо:</strong> {{ $order->payer_contact_person ?? '-' }} <br>--}}
                    {{--<strong>Телефон:</strong> {{ $order->payer_phone ?? '-' }} <br>--}}
                    {{--<strong>Дополнительная информация:</strong> {{ $order->payer_addition_info ?? '-' }}--}}
                {{--@endif--}}

                {{--@if(isset($order->payer_type) && $order->payer_type->slug == 'fizicheskoe-lico')--}}
                    {{--<strong>ФИО:</strong> {{ $order->payer_name ?? '-' }} <br>--}}
                    {{--<strong>Паспорт:</strong> {{ $order->payer_passport_series ?? '-' }} {{ $order->payer_passport_number ?? '-' }} <br>--}}
                    {{--<strong>Контактное лицо:</strong> {{ $order->payer_contact_person ?? '-' }} <br>--}}
                    {{--<strong>Телефон:</strong> {{ $order->payer_phone ?? '-' }} <br>--}}
                    {{--<strong>Дополнительная информация:</strong> {{ $order->payer_addition_info ?? '-' }}--}}
                {{--@endif--}}
            {{--@endif--}}
        </td>
    </tr>
    <tr>
        <th>Форма оплаты</th>
        <td>
            {{ $order->payment->name ?? '-' }}
        </td>
    </tr>
    <tr>
        <th>Заявку заполнил</th>
        <td>
            {{ $order->order_creator }}
            ({{ $order->order_creator_type_model->name }})
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>