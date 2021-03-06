@if(isset($order))
    <div class="row mb-3">
        @if($order->order_items->count())
            @foreach($order->order_items as $item)
                <div class="col-4">
                    <div class="order-item-container h-100 border p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        Длина:
                                    </div>
                                    <div class="col-5">
                                        {{ $item->length }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        Ширина:
                                    </div>
                                    <div class="col-5">
                                        {{ $item->width }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        Высота:
                                    </div>
                                    <div class="col-5">
                                        {{ $item->height }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        Обьем:
                                    </div>
                                    <div class="col-5">
                                        {{ $item->volume }}
                                    </div>
                                </div>
                            </div>
        {{--                    <div class="col-12 mt-1">--}}
        {{--                        <button class="btn btn-link text-danger" id="delete-item" data-item-id="{{ $item->id }}">Удалить</button>--}}
        {{--                    </div>--}}
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <span>Список габаритов пуст</span>
            </div>
        @endif
    </div>
@endif