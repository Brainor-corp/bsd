<!DOCTYPE html>
<html>
<body>
<h1>Новая заявка.</h1>
<table>
    <tbody>
    <tr>
        <th>Наименование груза</th>
        <td>{{ $order->name }}</td>
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
        <td>-</td>
    </tr>
    <tr>
        <th>Куда</th>
        <td>-</td>
    </tr>
    <tr>
        <th>Дополнительные услуги</th>
        <td>-</td>
    </tr>
    <tr>
        <th>Сумма страховки</th>
        <td>{{ $order->insurance_amount }}</td>
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