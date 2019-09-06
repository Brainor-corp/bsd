@if(isset($order))
    <div class="row mb-3">
        @if($order->order_services->count())
            @foreach($order->order_services as $service)
                <div class="col-4">
                    <div class="order-item-container h-100 border p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4">
                                        Название:
                                    </div>
                                    <div class="col">
                                        {{ $service->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4">
                                        Цена:
                                    </div>
                                    <div class="col">
                                        {{ $service->pivot->price }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <span>Список услуг пуст</span>
            </div>
        @endif
    </div>
@endif