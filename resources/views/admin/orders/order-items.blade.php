<div class="row">
    <div class="col-12">
        <h3>
            Список предметов
        </h3>
    </div>
    @foreach($order->items as $item)
        <div class="col-4">
            <div class="order-item-container">
                <div class="row">
                    <div class="col-12">
                        Название: {{ $item->name }}
                    </div>
                    <div class="col-12">
                        Длина: {{ $item->leght }}
                    </div>
                    <div class="col-12">
                        Ширина: {{ $item->width }}
                    </div>
                    <div class="col-12">
                        Высота: {{ $item->height }}
                    </div>
                    <div class="col-12">
                        Обьем: {{ $item->volume }}
                    </div>
                    <div class="col-12">
                        Тип: {{ $item->type->name }}
                    </div>
                    <div class="col-12 mt-1">
                        <button class="btn btn-link text-danger" id="delete-item" data-item-id="{{ $item->id }}">Удалить</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>