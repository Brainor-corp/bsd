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
        <td>{{ $order->sender_name }}</td>
        <td>{{ $order->recipient_name }}</td>
        <td>{{ $order->total_price }} <span>р</span></td>
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
            @endif
        </td>
    </tr>
@endforeach