<div class="row">
    <div class="col-12">
        <h3>
            Список предметов
        </h3>
    </div>
    @if($order->order_items->count())
        @foreach($order->order_items as $item)
            <div class="col-2">
                <div class="order-item-container h-100 border p-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    Название:
                                </div>
                                <div class="col-5">
                                    {{ $item->name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    Длина:
                                </div>
                                <div class="col-5">
                                    {{ $item->legth }}
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
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    Тип:
                                </div>
                                <div class="col-5">
                                    {{ $item->type->name }}
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
            <h5>Список предметов пуст</h5>
        </div>
    @endif
</div>