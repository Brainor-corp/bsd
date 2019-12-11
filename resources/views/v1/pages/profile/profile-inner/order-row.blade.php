<tr>
    <td>
        <span data-toggle="tooltip" title="Заявка на экспедирование">
            Заявка
        </span>
    </td>
    <td>{{ $order->id }}</td>
    <td>{{ $order->cargo_number }}</td>
    <td>{{ $order->cargo_status->name ?? '' }}</td>
    <td>{{ isset($order->order_date) ? $order->order_date->format('d.m.Y') : '' }}</td>
    <td>

        @if($order->order_items && count($order->order_items)>0)
            <div>
                <span class="label">Кол-во мест:</span>
                <span>{{ count($order->order_items) }}</span>
            </div>
            <div style="min-width: 100px;">
                <span class="label">Объем:</span>
                <span>{{ $order->actual_volume ?? $order->total_volume }} м<sup>3</sup></span>
            </div>
        @endif
        <div>
            <span class="label">Вес:</span>
            <span>{{ $order->actual_weight ?? $order->total_weight }} кг</span>
        </div>
    </td>
    <td>{{ $order->ship_city_name ?? $order->ship_city->name ?? '' }}</td>
    <td>{{ $order->dest_city_name ?? $order->dest_city->name ?? '' }}</td>
    <td>{{ $order->sender_name ?? ($order->sender_company_name ?? '') }}</td>
    <td>{{ $order->recipient_name ?? ($order->recipient_company_name ?? '') }}</td>
    <td>
        {{ $order->payment_status->name ?? '' }}
        @if(
            $order->status &&
            $order->status->slug === 'ispolnyaetsya' &&
            $order->payment_status &&
            $order->payment_status->slug === 'ne-oplachen' &&
            $order->payment &&
            $order->payment->slug === 'nalichnyy-raschet'
        )
            <br>
            <a href="{{ route('make-payment', ['order_id' => $order->id]) }}">Оплатить</a>
        @endif
    </td>
    <td>{{ $order->status->name }}</td>
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
