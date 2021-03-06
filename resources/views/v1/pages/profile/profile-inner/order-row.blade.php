<tr
    @if(
        (isset($order->status->name) && $order->status->name == 'Исполняется')
        || (isset($order->cargo_status->name) && $order->cargo_status->name == 'Груз в пути')
    )
        class="bg-light-green"
    @endif
>
    <td>
        <span data-toggle="tooltip" title="Заявка на экспедирование">
            Заявка
        </span>
    </td>
    <td>{{ $order->id }}</td>
    <td>{{ $order->number }}</td>
    <td>
        {{ $order->cargo_status->name ?? '' }}
    </td>
    <td>{{ isset($order->order_date) ? $order->order_date->format('d.m.Y') : '' }}</td>
    <td>

        @if($order->order_items && count($order->order_items)>0)
            <div>
                <span class="label">Кол-во мест:</span>
                <span>{{ count($order->order_items) }}</span>
            </div>
        @endif
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
    <td>{{ $order->sender_name ?? $order->sender_company_name }}</td>
    <td>{{ $order->recipient_name ?? $order->recipient_company_name }}</td>
    <td>
        {{ $order->payment_status->name ?? '' }}
    </td>
    <td>
        {{ $order->status->name ?? '' }}
    </td>
    <td>
        <a href="#"
           class="table-text-link show-order-documents"
           data-order-id="{{ $order->id }}"
           data-type="request"
        >
            Доступные<br/>документы
        </a>
    </td>
    <td>
        @if($order->status->slug === "chernovik")
            <a href="{{ route('calculator-show', ['id' => $order->id]) }}"
               class="table-icon-link"
               data-toggle="tooltip" title="Редактировать заявку"
            >
                <i class="fa fa-pencil-square-o"></i>
            </a>
        @else
            <a href="{{ route('report-show', ['id' => $order->id]) }}"
               class="table-icon-link"
               data-toggle="tooltip" title="Просмотреть заявку"
            >
                <i class="fa fa-eye"></i>
            </a>
            <a href="{{ route('calculator-show', ['id' => $order->id, 'repeat' => '1']) }}"
               class="table-icon-link"
               data-toggle="tooltip" title="Повторить заявку"
            >
                <i class="fa fa-undo"></i>
            </a>
        @endif
    </td>
</tr>
