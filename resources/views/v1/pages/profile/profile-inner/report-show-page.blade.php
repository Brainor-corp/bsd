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
                                @if($order->type->slug === 'order')
                                    <div class="calc__title">Дата</div>
                                    <div class="form-item row align-items-center">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label for="order_date">Дата исполнения (дата подачи авто)*</label>
                                                    <input
                                                            value="{{ old('order_date') ?? (isset($order) && $order->order_date ? $order->order_date->format('Y-m-d') : \Carbon\Carbon::now()->addDay()->format('Y-m-d')) }}"
                                                            type="date"
                                                            name="order_date"
                                                            id="order_date"
                                                            class="form-control"
                                                            readonly
                                                            disabled
                                                    >
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <span>Время исполнения заказа (время московское)</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label for="ship_time_from">С*</label>
                                                            <input
                                                                value="{{ old('ship_time_from') ?? (isset($order) && $order->ship_time_from ?
                                                                    \Carbon\Carbon::createFromFormat('H:i:s', $order->ship_time_from)->format('H:i')
                                                                    : '11:00')
                                                            }}"
                                                                type="time"
                                                                name="ship_time_from"
                                                                id="ship_time_from"
                                                                class="form-control"
                                                                readonly
                                                                disabled
                                                            >
                                                        </div>
                                                        <div class="col-6">
                                                            <label for="ship_time_to">До*</label>
                                                            <input
                                                                value="{{ old('ship_time_to') ?? (isset($order) && $order->ship_time_to ?
                                                                    \Carbon\Carbon::createFromFormat('H:i:s', $order->ship_time_to)->format('H:i')
                                                                    : '17:00')
                                                            }}"
                                                                type="time"
                                                                name="ship_time_to"
                                                                id="ship_time_to"
                                                                class="form-control"
                                                                readonly
                                                                disabled
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                                                <div class="col-sm col-12 d-none d-sm-block calc__inpgrp relative row__inf"
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
                                            @foreach($order->order_items as $key => $item)
                                                <div class="col-11 form-item row align-items-center package-item"
                                                     id="package-1" style="padding-right: 0;">
                                                    <label class="col-auto calc__label">
                                                        @if($loop->first)
                                                            <span>Габариты (м)*</span>
                                                            <span class="d-md-none d-inline-block">(Д/Ш/В/Вес/Кол-во)</span>
                                                        @endif
                                                    </label>
                                                    <div class="col-sm col-12 calc__inpgrp relative row__inf"
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
                                                <label class="col-auto calc__label">Вес груза (заяв-й, кг)*</label>
                                                <div class="col calc__inpgrp">
                                                    <input type="text" readonly id="total-weight" class="form-control"
                                                           value="{{ $order->total_weight }}">
                                                </div>
                                            </div>
                                            <div class="col-6 form-item row align-items-center text-right">
                                                <label class="col-auto calc__label">Объем (заяв-й, м<sup>3</sup>)*</label>
                                                <div class="col calc__inpgrp">
                                                    <input type="text" readonly id="total-volume" class="form-control"
                                                           value="{{ $order->total_volume }}">
                                                </div>
                                            </div>
                                        </div>
                                        @if(!empty($order->actual_weight) || !empty($order->actual_volume))
                                            <div class="row">
                                                @if(!empty($order->actual_weight))
                                                    <div class="col-6 form-item row align-items-center">
                                                        <label class="col-auto calc__label">Вес груза (факт-й, кг)*</label>
                                                        <div class="col calc__inpgrp">
                                                            <input type="text" readonly id="actual-weight" class="form-control"
                                                                   value="{{ $order->actual_weight }}">
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(!empty($order->actual_volume))
                                                    <div class="col-6 form-item row align-items-center text-right">
                                                        <label class="col-auto calc__label">Объем (факт-й, м<sup>3</sup>)*</label>
                                                        <div class="col calc__inpgrp">
                                                            <input type="text" readonly id="actual-volume" class="form-control"
                                                                   value="{{ $order->actual_volume }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="cargo_comment">Примечания по грузу</label>
                                        <textarea
                                                name="cargo_comment"
                                                id="cargo_comment"
                                                cols="30"
                                                rows="2"
                                                class="form-control"
                                                readonly
                                        >{{ old('cargo_comment') ?? ($order->cargo_comment ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-item row block-for-distance mt-3">
                                    <label class="col-auto calc__label big">Откуда</label>
                                    <div class="col delivery-block">
                                        <div class="form-item">
                                            <select id="ship_city" class="form-control point-select" readonly="" disabled>
                                                <option value="">{{ $order->ship_city_name ?? ($order->ship_city->name ?? '') }}</option>
                                            </select>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input disabled @if($order->take_need) checked @endif type="checkbox" class="custom-control-input delivery-checkbox" id="need-to-take">
                                            <label class="custom-control-label" for="need-to-take">Нужно забрать груз</label>
                                        </div>
                                        <div class="custom-control custom-radio d-none">
                                            <input type="radio" @if($order->take_need && $order->take_in_city) checked @endif class="custom-control-input need-to-take-input" id="need-to-take-type-in" disabled/>
                                            <label class="custom-control-label" for="need-to-take-type-in">в пределах города отправления</label>
                                        </div>
                                        <div class="custom-control custom-radio d-none">
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
                                            <input type="checkbox" @if($order->take_need && $order->take_point) checked @endif class="custom-control-input need-to-take-input x2-check" id="ship-from-point" disabled>
                                            <label class="custom-control-label" for="ship-from-point">Забор груза необходимо произвести в гипермаркете, распределительном центре или в точное время (временно́е "окно" менее 1 часа).</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-item row block-for-distance">
                                    <label class="col-auto calc__label big">Куда</label>
                                    <div class="col delivery-block">
                                        <div class="form-item">
                                            <select id="dest_city" class="form-control point-select" name="dest_city" readonly="" disabled>
                                                <option value="">{{ $order->dest_city_name ?? ($order->dest_city->name ?? '') }}</option>
                                            </select>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input disabled type="checkbox" @if($order->delivery_need) checked @endif class="custom-control-input delivery-checkbox" />
                                            <label class="custom-control-label" for="need-to-bring">Нужно доставить груз</label>
                                        </div>
                                        <div class="custom-control custom-radio d-none">
                                            <input type="radio" @if($order->delivery_need && $order->delivery_in_city) checked @endif class="custom-control-input need-to-bring-input" disabled/>
                                            <label class="custom-control-label" for="need-to-bring-type-in">в пределах города назначения</label>
                                        </div>
                                        <div class="custom-control custom-radio d-none">
                                            <input @if($order->delivery_need && !$order->delivery_in_city) checked @endif type="radio" class="custom-control-input need-to-bring-input" disabled/>
                                            <label class="custom-control-label" for="need-to-bring-type-from">в:</label>
                                        </div>
                                        <div class="form-item ininner">
                                            <div class="relative">
                                                <i class="dropdown-toggle fa-icon"></i>
                                                <input class="form-control suggest_address need-to-bring-input-address" value="{{ $order->delivery_address }}" id="dest_point" name="dest_point" placeholder="Название населенного пункта или адрес" disabled>
                                            </div>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input @if($order->delivery_need && $order->delivery_point) checked @endif type="checkbox" class="custom-control-input need-to-bring-input x2-check" disabled>
                                            <label class="custom-control-label" for="bring-to-point">Доставку груза необходимо произвести в гипермаркет, распределительный центр или в точное время (временно́е "окно" менее 1 часа).</label>
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
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="insurance"
                                               disabled
                                               {{ $order->insurace ? 'checked' : '' }}
                                        >
                                        <label class="custom-control-label" for="insurance">Страхование</label>
                                    </div>
                                    <div id="insurance-amount-wrapper"
                                         style="{{ $order->insurace ? '' : 'display: none;' }}"
                                    >
                                        <br>
                                        <label class="" for="insurance-amount">Сумма страховки</label>
                                        <input type="text" readonly class="form-control" id="insurance-amount"
                                               placeholder="Введите сумму страховки"
                                               value="{{ $order->insurance }}">
                                        <br>
                                    </div>
                                    <div class="relative">
                                        <label class="" for="discount">Скидка (%)</label>
                                        <input type="number"
                                               class="form-control"
                                               id="discount"
                                               name="discount"
                                               value="{{ $order->discount ?? 0 }}"
                                               disabled>
                                    </div>
                                </div>

                                <div class="calc__title">Отправитель</div>
                                @foreach($userTypes as $userType)
                                    <div class="custom-control custom-radio">
                                        <input type="radio" data-slug="{{ $userType->slug }}" class="custom-control-input" @if(isset($order->sender_type) && $order->sender_type_id == $userType->id) checked @endif id="sender_type_{{ $userType->slug }}" value="{{ $userType->id }}" name="sender_type_id" disabled />
                                        <label class="custom-control-label" for="sender_type_{{ $userType->slug }}">{{ $userType->name }}</label>
                                    </div>
                                @endforeach
                                <div class="sender-forms">
                                    <div class="legal"
                                         @if(isset($order->sender_type) && $order->sender_type->slug == 'yuridicheskoe-lico') style="display: block" @else style="display: none" @endif
                                    >
                                        @include('v1.partials.calculator.sender-forms.legal-type-form', ['disabled' => true])
                                    </div>
                                    <div class="individual"
                                         @if(isset($order->sender_type) && $order->sender_type->slug == 'fizicheskoe-lico') style="display: block" @else style="display: none" @endif
                                    >
                                        @include('v1.partials.calculator.sender-forms.individual-type-form', ['disabled' => true])
                                    </div>
                                </div>

                                <div class="calc__title">Получатель</div>
                                @foreach($userTypes as $userType)
                                    <div class="custom-control custom-radio">
                                        <input type="radio" data-slug="{{ $userType->slug }}" class="custom-control-input" id="recipient_type_{{ $userType->slug }}" @if(isset($order->recipient_type) && $order->recipient_type_id == $userType->id) checked @endif value="{{ $userType->id }}" name="recipient_type_id" disabled />
                                        <label class="custom-control-label" for="recipient_type_{{ $userType->slug }}">{{ $userType->name }}</label>
                                    </div>
                                @endforeach
                                <div class="recipient-forms">
                                    <div class="legal"
                                         @if(isset($order->recipient_type) && $order->recipient_type->slug == 'yuridicheskoe-lico') style="display: block" @else style="display: none" @endif
                                    >
                                        @include('v1.partials.calculator.recipient-forms.legal-type-form', ['disabled' => true])
                                    </div>
                                    <div class="individual"
                                         @if(isset($order->recipient_type) && $order->recipient_type->slug == 'fizicheskoe-lico') style="display: block" @else style="display: none" @endif
                                    >
                                        @include('v1.partials.calculator.recipient-forms.individual-type-form', ['disabled' => true])
                                    </div>
                                </div>

                                <div class="calc__title">Данные плательщика</div>
                                <div id="payer-email-wrapper">
                                    <label class="" for="payer-email">E-Mail плательщика</label>
                                    <input type="email"
                                           class="form-control"
                                           id="payer-email"
                                           name="payer-email"
                                           value="{{ $order->payer_email }}"
                                           disabled
                                    >
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" @if(isset($order->payer) && $order->payer->slug === 'otpravitel') checked @endif class="custom-control-input" id="sender" name="payer_type" value="otpravitel" disabled />
                                    <label class="custom-control-label" for="sender">Отправитель</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" @if(isset($order->payer) && $order->payer->slug === 'poluchatel') checked @endif class="custom-control-input" id="recipient" name="payer_type" value="poluchatel" disabled />
                                    <label class="custom-control-label" for="recipient">Получатель</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" @if(isset($order->payer) && $order->payer->slug === '3-e-lico') checked @endif class="custom-control-input" id="3rd-person" name="payer_type" value="3-e-lico" disabled />
                                    <label class="custom-control-label" for="3rd-person">3-е лицо</label>
                                </div>
                                <div id="3rd-person-payer"  @if(!isset($order->payer) || $order->payer->slug !== '3-e-lico') style="display: none" @endif>
                                    @foreach($userTypes as $userType)
                                        <div class="custom-control custom-radio">
                                            <input type="radio" data-slug="{{ $userType->slug }}" class="custom-control-input req" id="payer_type_{{ $userType->slug }}" @if(isset($order->payer_form_type) && $order->payer_form_type_id == $userType->id) checked @endif value="{{ $userType->id }}" name="payer_form_type_id" disabled />
                                            <label class="custom-control-label" for="payer_type_{{ $userType->slug }}">{{ $userType->name }}</label>
                                        </div>
                                    @endforeach
                                    <div class="payer-forms">
                                        <div class="legal"
                                             @if(isset($order->payer_form_type) && $order->payer_form_type->slug == 'yuridicheskoe-lico') style="display: block" @else style="display: none" @endif
                                        >
                                            @include('v1.partials.calculator.payer-forms.legal-type-form', ['disabled' => true])
                                        </div>
                                        <div class="individual"
                                             @if(isset($order->payer_form_type) && $order->payer_form_type->slug == 'fizicheskoe-lico') style="display: block" @else style="display: none" @endif
                                        >
                                            @include('v1.partials.calculator.payer-forms.individual-type-form', ['disabled' => true])
                                        </div>
                                    </div>
                                </div>
                                <div class="calc__title">Форма оплаты</div>
                                <div class="custom-control custom-radio">
                                    <input disabled @if(isset($order->payment) && $order->payment->slug === 'nalichnyy-raschet') checked @endif type="radio" class="custom-control-input" id="available">
                                    <label class="custom-control-label" for="available">Наличный расчет</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input disabled @if(isset($order->payment) && $order->payment->slug === 'beznalichnyy-raschet') checked @endif type="radio" class="custom-control-input" id="non-cash">
                                    <label class="custom-control-label" for="non-cash">Безналичный расчет</label>
                                </div>
                                <div class="calc__title">Заявку заполнил</div>
                                <div id="order-creator-wrapper">
                                    <label class="" for="order-creator">ФИО</label>
                                    <input type="text"
                                           class="form-control"
                                           id="order-creator"
                                           name="order-creator"
                                           placeholder="ФИО"
                                           value="{{ old('order-creator') ?? ($order->order_creator ?? (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->full_name : '')) }}"
                                           disabled>
                                    <br>
                                </div>
                                <div>
                                    <label class="mb-0" for="">Тип</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio"
                                               class="custom-control-input"
                                               id="order-creator-type-sender"
                                               value="otpravitel-1"
                                               name="order-creator-type"
                                               @if(isset($order->order_creator_type_model->slug) && $order->order_creator_type_model->slug === 'otpravitel-1')
                                               checked
                                               @endif
                                               disabled
                                        />
                                        <label class="custom-control-label" for="order-creator-type-sender">Отправитель</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio"
                                               class="custom-control-input"
                                               id="order-creator-type-recipient"
                                               value="poluchatel-1"
                                               name="order-creator-type"
                                               @if(isset($order->order_creator_type_model->slug) && $order->order_creator_type_model->slug === 'poluchatel-1')
                                               checked
                                               @endif
                                               disabled
                                        />
                                        <label class="custom-control-label" for="order-creator-type-recipient">Получатель</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio"
                                               class="custom-control-input"
                                               id="order-creator-type-payer"
                                               value="platelshchik"
                                               name="order-creator-type"
                                               @if(isset($order->order_creator_type_model->slug) && $order->order_creator_type_model->slug === 'platelshchik')
                                               checked
                                               @endif
                                               disabled
                                        />
                                        <label class="custom-control-label" for="order-creator-type-payer">Плательщик</label>
                                    </div>
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
                                                  data-base-price="договорная">{{ $order->base_price }}</span>
                                            <span class="rouble">p</span>
                                        </span>
                                    </div>

                                    @if($order->take_need || $order->delivery_need)
                                        <div id="delivery-total-wrapper">
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
                                                                    Забор груза: {{ $order->take_city_name }}
                                                                    @if($order->take_distance)<small> ({{ $order->take_distance }} км) </small>@endif
                                                                    @if($order->take_polygon) ({{ $order->take_polygon->name }})@endif
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
                                            @if($order->delivery_need)
                                                <div class="custom-service-total-item">
                                                    <div class="block__itogo_item d-flex">
                                                        <div class="d-flex flex-wrap" id="services-total-names">
                                                            <span class="block__itogo_value">
                                                                Доставка груза: {{ $order->delivery_city_name }}
                                                                @if($order->delivery_distance)<small> ({{ $order->delivery_distance }} км) </small>@endif
                                                                @if($order->bring_polygon)({{ $order->bring_polygon->name }})@endif
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
                                    <div id="custom-services-total-wrapper">
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
                                                                {{ $service->pivot->price }}
                                                            </span>
                                                            <span class="rouble">p</span>
                                                        </span>
                                                    </div>
                                                @endforeach
                                                @if($order->insurance)
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
                                                @endif
                                                @if(isset($order->discount_amount))
                                                    <div class="block__itogo_item d-flex">
                                                        <div class="d-flex flex-wrap" id="services-total-names">
                                                            <span class="block__itogo_value">Скидка</span>
                                                        </div>
                                                        <span class="block__itogo_price d-flex flex-nowrap" id="services-total-prices">
                                                            <span class="block__itogo_amount">
                                                                {{ $order->discount_amount }}
                                                            </span>
                                                            <span class="rouble">p</span>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator-hr"></div>
                                    <footer class="block__itogo_footer d-flex">
                                        <span>Стоимость перевозки*</span>
                                        <span class="block__itogo_price d-flex flex-nowrap">
                                            <span class="block__itogo_amount">
                                                <span id="total-price">{{ $order->total_price }}</span>
                                            </span>
                                            <span class="rouble">p</span>
                                            <span id="total-volume"></span>
                                        </span>
                                    </footer>
                                    @if(!empty($order->actual_price) && $order->actual_price != $order->total_price)
                                        <footer class="block__itogo_footer d-flex">
                                            <span>Стоимость перевозки (фактическая)</span>
                                            <span class="block__itogo_price d-flex flex-nowrap">
                                                <span class="block__itogo_amount">
                                                    <span id="total-price">{{ $order->actual_price }}</span>
                                                </span>
                                                <span class="rouble">p</span>
                                                <span id="total-volume"></span>
                                            </span>
                                        </footer>
                                    @endif
                                    <div class="text-right">
                                        <br>
                                        {{ $order->payment_status->name ?? '' }}
                                    </div>
                                </div>
                                <div class="annotation-text">* - Предварительный расчет. Точная стоимость доставки будет определена после обмера груза специалистами компании БСД на складе.</div>
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
