@if(isset($order))
    <div class="row mt-4">
        <div class="col-12">
            <h3>
                Список услуг
            </h3>
        </div>
        @if($order->order_services->count())
            @foreach($order->order_services as $service)
                <div class="col-4">
                    <div class="order-item-container h-100 border p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        Название:
                                    </div>
                                    <div class="col-5">
                                        {{ $service->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        Цена:
                                    </div>
                                    <div class="col-3">
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
                <h5>Список услуг пуст</h5>
            </div>
        @endif
    </div>
@endif