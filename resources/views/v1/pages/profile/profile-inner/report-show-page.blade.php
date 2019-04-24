@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/klientam') }}" class="">Клиентам</a></span>
            <span class="breadcrumb__item"><a href="{{ route('report-list') }}" class="">Отчеты</a></span>
            <span class="breadcrumb__item">Отчет</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Отчет №{{ $order->id }}</h1>
                    </header>
                    <div class="row">
                        <div class="col-md-6">
                            <form class="calculator-form" action="/calculator-show" method="post">
                                <div class="calc__title">Груз</div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Наименование груза*</label>
                                    <div class="col">
                                        <input type="text" readonly class="form-control"
                                               placeholder="Введите наименование груза"
                                               value="{{ $order->shipping_name }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-11 form-item row align-items-center"
                                                 style="padding-right: 0;">
                                                <label class="col-auto calc__label"></label>
                                                <div class="col calc__inpgrp relative row__inf"
                                                     style="padding-right: 0;">
                                                    <div class="input-group">
                                                        <span class="form-control dimensions-label text-center">Д</span>
                                                        <span class="form-control dimensions-label text-center">Ш</span>
                                                        <span class="form-control dimensions-label text-center">В</span>
                                                        <span class="form-control dimensions-label text-center">Вес</span>
                                                        <span class="form-control dimensions-label text-center">Кол-во</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @php($totalVolume = 0)
                                            @foreach($order->order_items as $item)
                                                @php($totalVolume += $item->volume)
                                                <div class="col-11 form-item row align-items-center package-item"
                                                     id="package-1" style="padding-right: 0;">
                                                    <label class="col-auto calc__label">Габариты (м)*</label>
                                                    <div class="col calc__inpgrp relative row__inf"
                                                         style="padding-right: 0;">
                                                        <div class="input-group">
                                                            <input type="text" readonly id="packages_1_length"
                                                                   class="form-control text-center package-params package-dimensions"
                                                                   placeholder="Длина"
                                                                   value="{{ $item->length }}">
                                                            <input type="text" readonly id="packages_1_width"
                                                                   class="form-control text-center package-params package-dimensions"
                                                                   placeholder="Ширина"
                                                                   value="{{ $item->width }}">
                                                            <input type="text" readonly id="packages_1_height"
                                                                   class="form-control text-center package-params package-dimensions"
                                                                   name="cargo[packages][1][height]"
                                                                   placeholder="Высота"
                                                                   value="{{ $item->height }}">
                                                            <input type="text" readonly id="packages_1_weight"
                                                                   class="form-control text-center package-params package-weight"
                                                                   placeholder="Вес"
                                                                   value="{{ $item->weight }}">
                                                            <input type="text" readonly id="packages_1_quantity"
                                                                   class="form-control text-center package-params package-quantity"
                                                                   placeholder="Кол-во"
                                                                   value="{{ $item->quantity }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row">
                                            <div class="col-6 form-item row align-items-center">
                                                <label class="col-auto calc__label">Вес груза (кг)*</label>
                                                <div class="col calc__inpgrp">
                                                    <input type="text" readonly id="total-weight" class="form-control"
                                                           value="{{ $order->total_weight }}">
                                                </div>
                                            </div>
                                            <div class="col-6 form-item row align-items-center text-right">
                                                <label class="col-auto calc__label">Объем (м<sup>3</sup>)*</label>
                                                <div class="col calc__inpgrp">
                                                    <input type="text" readonly id="total-volume" class="form-control"
                                                           value="{{ $totalVolume }}">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-item row block-for-distance">
                                    <label class="col-auto calc__label big">Откуда</label>
                                    <div class="col delivery-block">
                                        <div class="form-item">
                                            <select id="ship_city" class="form-control point-select">
                                                <option value="">{{ $order->ship_city_name ?? $order->ship_city->name ?? '' }}</option>
                                            </select>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input disabled @if($order->take_need) checked @endif type="checkbox" class="custom-control-input delivery-checkbox" id="need-to-take">
                                            <label class="custom-control-label" for="need-to-take">Нужно забрать груз</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" @if($order->take_need && $order->take_in_city) checked @endif class="custom-control-input need-to-take-input" id="need-to-take-type-in" disabled/>
                                            <label class="custom-control-label" for="need-to-take-type-in">в пределах города отправления</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" @if($order->take_need && !$order->take_in_city) checked @endif class="custom-control-input need-to-take-input" id="need-to-take-type-from" disabled/>
                                            <label class="custom-control-label" for="need-to-take-type-from">из:</label>
                                        </div>
                                        <div class="form-item ininner">
                                            <div class="relative">
                                                <i class="dropdown-toggle fa-icon"></i>
                                                <input readonly value="{{ $order->take_address }}" class="form-control suggest_address need-to-take-input-address" id="ship_point" placeholder="Название населенного пункта или адрес"  disabled>
                                            </div>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" @if($order->take_need && !$order->take_point) checked @endif class="custom-control-input need-to-take-input x2-check" id="ship-from-point" disabled>
                                            <label class="custom-control-label" for="ship-from-point">Доставку груза необходимо произвести в гипермаркете, распределительном центре или в точное время (временно́е "окно" менее 1 часа).</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-item row block-for-distance">
                                    <label class="col-auto calc__label big">Куда</label>
                                    <div class="col delivery-block">
                                        <div class="form-item">
                                            <select id="dest_city" class="form-control point-select" name="dest_city">
                                                <option value="">{{ $order->dest_city_name ?? $order->dest_city->name ?? '' }}</option>
                                            </select>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input disabled type="checkbox" @if($order->delivery_need) checked @endif class="custom-control-input delivery-checkbox" />
                                            <label class="custom-control-label" for="need-to-bring">Нужно доставить груз</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" @if($order->delivery_need && $order->delivery_in_city) checked @endif class="custom-control-input need-to-bring-input" disabled/>
                                            <label class="custom-control-label" for="need-to-bring-type-in">в пределах города отправления</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input @if($order->delivery_need && !$order->delivery_in_city) checked @endif type="radio" class="custom-control-input need-to-bring-input" disabled/>
                                            <label class="custom-control-label" for="need-to-bring-type-from">в:</label>
                                        </div>
                                        <div class="form-item ininner">
                                            <div class="relative">
                                                <i class="dropdown-toggle fa-icon"></i>
                                                <input class="form-control suggest_address need-to-bring-input-address" id="dest_point" name="dest_point" placeholder="Название населенного пункта или адрес" disabled>
                                            </div>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input @if($order->delivery_need && $order->delivery_point) checked @endif type="checkbox" class="custom-control-input need-to-bring-input x2-check" disabled>
                                            <label class="custom-control-label" for="bring-to-point">Забор груза необходимо произвести в гипермаркете, распределительном центре или в точное время (временно́е "окно" менее 1 часа).</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="calc__title">Дополнительные услуги</div>
                                <div class="form-item form-group-additional">
                                    <div class="custom-control custom-checkbox">
                                        <input disabled @if($order->order_services->where('slug', 'myagkaya-upakovka')->count()) checked @endif type="checkbox" class="custom-control-input custom-service-checkbox" id="myagkaya-upakovka">
                                        <label class="custom-control-label" for="myagkaya-upakovka">Упаковать груз в мягкую упаковку (стрейч-пленка)?</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input disabled @if($order->order_services->where('slug', 'obreshetka')->count()) checked @endif type="checkbox" class="custom-control-input custom-service-checkbox" id="obreshetka">
                                        <label class="custom-control-label" for="obreshetka">Упаковать груз в жесткую упаковку (обрешетка)?</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input disabled @if($order->order_services->where('slug', 'palletirovanie')->count()) checked @endif type="checkbox" class="custom-control-input custom-service-checkbox" id="palletirovanie">
                                        <label class="custom-control-label" for="palletirovanie">Паллетировать грузовые места?</label>
                                    </div>
                                </div>
                                <div class="form-item form-group-additional">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="insurance" disabled checked>
                                        <label class="custom-control-label" for="insurance">Страхование</label>
                                    </div>
                                    <div id="insurance-amount-wrapper">
                                        <br>
                                        <label class="" for="insurance-amount">Сумма страховки</label>
                                        <input type="text" readonly class="form-control" id="insurance-amount"
                                               placeholder="Введите сумму страховки"
                                               value="{{ $order->insurance_amount }}">
                                        <br>
                                    </div>
                                    <div class="relative">
                                        <i class="dropdown-toggle fa-icon"></i>
                                        <select class="custom-select" id="discount" name="discount">
                                            <option disabled selected>{{ $order->discount ? $order->discount . '%' : 'Нет скидки'}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="calc__title">Отправитель</div>

                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">ФИО</label>
                                    <div class="col"><input value="{{ $order->sender_name }}" type="text" readonly class="form-control"></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Телефон*</label>
                                    <div class="col calc__inpgrp"><input type="text" value="{{ $order->sender_phone }}" readonly class="form-control"></div>
                                </div>

                                <div class="calc__title">Получатель</div>

                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">ФИО</label>
                                    <div class="col"><input type="text" value="{{ $order->recepient_name }}" readonly class="form-control"></div>
                                </div>

                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Телефон*</label>
                                    <div class="col calc__inpgrp"><input type="text" value="{{ $order->recepient_phone }}" readonly class="form-control"></div>
                                </div>

                                <div class="calc__title">Данные плательщика</div>
                                <div class="custom-control custom-radio">
                                    <input disabled @if($order->payer->slug === 'otpravitel') checked @endif type="radio" class="custom-control-input" id="sender">
                                    <label class="custom-control-label" for="sender">Отправитель</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input disabled @if($order->payer->slug === 'poluchatel') checked @endif type="radio" class="custom-control-input" id="recipient">
                                    <label class="custom-control-label" for="recipient">Получатель</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input disabled @if($order->payer->slug === '3-e-lico') checked @endif type="radio" class="custom-control-input" id="3rd-person">
                                    <label class="custom-control-label" for="3rd-person">3-е лицо</label>
                                </div>
                                @if($order->payer->slug === '3-e-lico')
                                    <div id="3rd-person-payer">
                                        <div class="form-item row align-items-center">
                                            <label class="col-auto calc__label">ФИО</label>
                                            <div class="col calc__inpgrp">
                                                <input value="{{ $order->payer_name }}" type="text" readonly class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-item row align-items-center">
                                            <label class="col-auto calc__label">Телефон</label>
                                            <div class="col calc__inpgrp">
                                                <input value="{{ $order->payer_phone }}"  type="text" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="calc__title">Форма оплаты</div>
                                <div class="custom-control custom-radio">
                                    <input disabled @if($order->payment->slug === 'nalichnyy-raschet') checked @endif type="radio" class="custom-control-input" id="available">
                                    <label class="custom-control-label" for="available">Наличный расчет</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input disabled @if($order->payment->slug === 'beznalichnyy-raschet') checked @endif type="radio" class="custom-control-input" id="non-cash">
                                    <label class="custom-control-label" for="non-cash">Безналичный расчет</label>
                                </div>
{{--                                <div class="form-item d-flex">--}}
{{--                                    <button class="btn margin-item btn-danger">Оформить заказ</button>--}}
{{--                                    <button class="btn margin-item btn-default">Сохранить черновик</button>--}}
{{--                                </div>--}}
                            </form>
                        </div>
                        <div class="col-md-4 offset-md-2">
                            <section class="block__itogo">
                                <div class="block__itogo-inner">
                                    <header class="block__itogo_title">Перевозка груза включает</header>

                                    <div class="block__itogo_item d-flex">
                                        <div class="d-flex flex-wrap">
                                            <span class="block__itogo_label">Межтерминальная перевозка:</span>
                                            <span class="block__itogo_value">{{ $order->ship_city_name ?? $order->ship_city->name }} → {{ $order->dest_city_name ?? $order->dest_city->name }}</span>
                                        </div>
                                        <span class="block__itogo_price d-flex flex-nowrap">
                                            <span class="block__itogo_amount" id="base-price"
                                                  data-base-price="договорная">договорная</span>
                                            <span class="rouble">p</span>
                                        </span>
                                    </div>

                                    @if($order->take_need || $order->delivery_need)
                                        <div id="delivery-total-wrapper" style="display: none;">
                                            <div class="block__itogo_item d-flex">
                                                <div class="d-flex flex-wrap">
                                                    <span class="block__itogo_label">Доставка:</span>
                                                </div>
                                            </div>
                                            @if($order->take_need)
                                                <div id="delivery-total-list">
                                                    <div class="custom-service-total-item">
                                                        <div class="block__itogo_item d-flex">
                                                            <div class="d-flex flex-wrap" id="services-total-names">
                                                                <span class="block__itogo_value">
                                                                    Забор груза: ?? @if($order->take_distance)<small> ({{ $order->take_distance }} км) </small>@endif
                                                                </span>
                                                            </div>
                                                            <span class="block__itogo_price d-flex flex-nowrap" id="services-total-prices">
                                                                <span class="block__itogo_amount takePrice">{{ $order->take_price }}</span>
                                                                <span class="rouble">p</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($order->delvery_need)
                                                <div class="custom-service-total-item">
                                                    <div class="block__itogo_item d-flex">
                                                        <div class="d-flex flex-wrap" id="services-total-names">
                                                            <span class="block__itogo_value">
                                                                Доставка груза: ?? @if($order->delicery_distance)<small> ({{ $order->delicery_distance }} км) </small>@endif
                                                            </span>
                                                        </div>
                                                        <span class="block__itogo_price d-flex flex-nowrap" id="services-total-prices">
                                                            <span class="block__itogo_amount bringPrice">{{ $order->delivery_price }}</span>
                                                            <span class="rouble">p</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <div id="custom-services-total-wrapper" style="display: block;">
                                        <div class="block__itogo_item d-flex">
                                            <div class="d-flex flex-wrap">
                                                <span class="block__itogo_label">Дополнительные услуги:</span>
                                            </div>
                                        </div>
                                        <div id="custom-services-total-list">
                                            <div class="custom-service-total-item">
                                                @foreach($order->order_services as $service)
                                                    <div class="block__itogo_item d-flex">
                                                        <div class="d-flex flex-wrap" id="services-total-names">
                                                            <span class="block__itogo_value">{{ $service->name }}</span>
                                                        </div>
                                                        <span class="block__itogo_price d-flex flex-nowrap" id="services-total-prices">
                                                            <span class="block__itogo_amount">
                                                                {{ $service->price }}
                                                            </span>
                                                            <span class="rouble">p</span>
                                                        </span>
                                                    </div>
                                                @endforeach
                                                <div class="block__itogo_item d-flex">
                                                    <div class="d-flex flex-wrap" id="services-total-names">
                                                        <span class="block__itogo_value">Страхование</span>
                                                    </div>
                                                    <span class="block__itogo_price d-flex flex-nowrap" id="services-total-prices">
                                                        <span class="block__itogo_amount">
                                                            {{ $order->insurance_amount }}
                                                        </span>
                                                        <span class="rouble">p</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator-hr"></div>
                                    <footer class="block__itogo_footer d-flex">
                                        <span>Стоимость перевозки</span>
                                        <span class="block__itogo_price d-flex flex-nowrap">
                                            <span class="block__itogo_amount">
                                                <span id="total-price">{{ $order->total_price }}</span>
                                            </span>
                                            <span class="rouble">p</span>
                                            <span id="total-volume"></span>
                                        </span>
                                    </footer>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footScripts')
    <script src="{{ asset('v1/js/profile.js') }}"></script>
@endsection