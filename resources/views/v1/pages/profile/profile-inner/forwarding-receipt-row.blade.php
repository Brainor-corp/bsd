<tr
    @if(
        (isset($order->status->name) && $order->status->name == 'Исполняется')
        || (isset($order->cargo_status->name) && $order->cargo_status->name == 'Груз в пути')
    )
        class="bg-light-green"
    @endif
>
    <td>
        <span data-toggle="tooltip" title="Экспедиторская расписка">
            ЭР
        </span>
    </td>
    <td></td>
    <td>{{ $order->number }}</td>
    <td>
        {{ $order->cargo_status->name ?? '' }}
    </td>
    <td>{{ $order->order_date->format('d.m.Y') }}</td>
    <td>
        <div>
            <span class="label">Кол-во мест:</span>
            <span>{{ $order->packages_count }}</span>
        </div>
        <div style="min-width: 100px;">
            <span class="label">Объем:</span>
            <span>{{ $order->volume }} м<sup>3</sup></span>
        </div>
        <div>
            <span class="label">Вес:</span>
            <span>{{ $order->weight }} кг</span>
        </div>
    </td>
    <td>{{ $order->ship_city }}</td>
    <td>{{ $order->dest_city }}</td>
    <td>{{ $order->sender_name }}</td>
    <td>{{ $order->recipient_name }}</td>
    <td>
        {{ $order->payment_status->name ?? '' }}
    </td>
    <td></td>
    <td>
        <a href="#"
           class="table-text-link show-order-documents"
           data-order-id="{{ $order->id }}"
           data-type="receipt"
        >
            Доступные<br/>документы
            @if(isset($order->payment_status) && $order->payment_status->name === 'Не оплачена')
                <span class="text-danger">Оплатить онлайн</span>
            @endif
        </a>
    </td>
    <td></td>
</tr>
