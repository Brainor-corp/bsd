@foreach($orders as $order)
    <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->created_at->format('d.m.Y') }}</td>
        <td>
            <div>
                <span class="label">Вес:</span>
                <span>{{ $order->total_weight }} кг</span>
            </div>
            <div>
                <a class="show-order-items" data-order-id="{{ $order->id }}"
                   href="#">Показать габариты</a>
            </div>
        </td>
        <td>{{ $order->ship_city_name ?? $order->ship_city->name ?? '' }}</td>
        <td>{{ $order->dest_city_name ?? $order->dest_city->name ?? '' }}</td>
        <td>{{ $order->sender_name ?? ($order->sender_company_name ?? '') }}</td>
        <td>{{ $order->recipient_name ?? ($order->recipient_company_name ?? '') }}</td>
        <td>
            {{ $order->actual_price ?? ($order->total_price ?? '-' )}} <span>р</span>
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
            @if($order->payment_status->slug === 'oplachen')
                ({{ $order->payment_status->name }})
            @endif
        </td>
        <td>{{ $order->status->name }}</td>
        <td>
            <a href="#" class="table-text-link show-order-documents" data-order-id="{{ $order->id }}">
                Доступные<br/>документы
            </a>
        </td>
        <td>
            @if($order->status->slug === "chernovik")
                <a href="{{ route('calculator-show', ['id' => $order->id]) }}" class="table-icon-link"><i class="fa fa-pencil-square-o"></i></a>
            @else
                <a href="{{ route('report-show', ['id' => $order->id]) }}" class="table-icon-link"><i class="fa fa-eye"></i></a>
                <a href="{{ route('calculator-show', ['id' => $order->id, 'repeat' => '1']) }}" class="table-icon-link"><i class="fa fa-undo"></i></a>
            @endif
        </td>
    </tr>
@endforeach
