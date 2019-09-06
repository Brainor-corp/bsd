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
            {{ $order->ship_city_name ?? ($order->ship_city->name ?? '') }}
            @if($order->take_need)
                Нужно забрать груз.
                @if($order->take_in_city)
                    В пределах города отправления
                @else
                    Из: {{ $order->take_address }}
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
            {{ $order->dest_city_name ?? ($order->dest_city->name ?? '') }}
            @if($order->delivery_need)
                Нужно доставить груз.
                @if($order->delivery_in_city)
                    В пределах города назначения
                @else
                    В: {{ $order->delivery_address }}
                @endif

                @if($order->delivery_point)
                    Доставку груза необходимо произвести в гипермаркет, распределительный центр или в точное время (временно́е "окно" менее 1 часа).
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <th>Дополнительные услуги</th>
        <td>-</td>
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
        <td>-</td>
    </tr>
    <tr>
        <th>Получатель</th>
        <td>-</td>
    </tr>
    <tr>
        <th>Плательщик</th>
        <td>-</td>
    </tr>
    <tr>
        <th>Форма оплаты</th>
        <td>-</td>
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