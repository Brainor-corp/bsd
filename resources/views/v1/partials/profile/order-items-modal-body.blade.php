<ul>
    @foreach($order->order_items as $item)
        <li>
            ДхШхВ: {{ $item->length }}х{{ $item->width }}х{{ $item->height }} м
        </li>
    @endforeach
</ul>