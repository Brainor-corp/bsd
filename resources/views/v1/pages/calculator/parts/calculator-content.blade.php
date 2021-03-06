<form class="calculator-form" action="{{ route('order-save-action') }}" method="post">
    @csrf
    @if(isset($order) && empty(request()->get('repeat')))
        <input type="hidden" name="order_id" value="{{ $order->id }}">
    @endif
    <input type="hidden" name="type" value="{{ $orderType }}">
    <div id="hiddenMap" class="d-none"></div>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    @if($orderType === 'order')
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
                                required
                        >
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="row mb-2">
                            <div class="col-12">
                                <span>Время исполнения заказа (время местное)</span>
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
                                    required
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
                                    required
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="form-item row align-items-center">
        <div class="col">
            <label for="warehouse_schedule">Режим работы склада*</label>
            <input
                value="{{ old('warehouse_schedule') ?? ($order->warehouse_schedule ?? '')}}"
                type="text"
                name="warehouse_schedule"
                id="warehouse_schedule"
                class="form-control"
                placeholder="с 00 до 24"
                maxlength="255"
                required
            >
        </div>
    </div>
    <div class="calc__title mt-3">Груз</div>
    <div class="form-item row align-items-center">
        <label class="col-auto calc__label">Наименование груза*</label>
        <div class="col">
            {{--<input type="text" class="form-control" placeholder="Введите наименование груза" name="cargo[name]" value="{{ $order->shipping_name ?? '' }}" required>--}}
            <select class="form-control cargo-type-select" data-live-search="true" name="cargo[name]" required>
                <option value="" selected disabled>Выберите тип груза</option>
                @foreach($cargoTypes->sortBy(function ($type, $key) {
                        return mb_strtolower($type['name']);
                }) as $cargoType)
                    <option value="{{ $cargoType->id }}"
                    @if(!empty(old('cargo.name')))
                        @if(!empty(old('cargo.name')) && intval(old('cargo.name')) === $cargoType->id) selected @endif
                    @else
                        @if(isset($order->cargo_type) && $cargoType->id == $order->cargo_type)
                            selected
                        @elseif(isset($order->shipping_name) && $cargoType->name == $order->shipping_name)
                            selected
                        @endif
                    @endif
                    >{{ $cargoType->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row d-none d-sm-block">
                <div class="col-11 form-item row align-items-center" style="padding-right: 0;">
                    <label class="col-auto calc__label"></label>
                    <div class="col-sm col-12 calc__inpgrp relative row__inf"  style="padding-right: 0;">
                        <div class="input-group">
                            <span class="form-control dimensions-label text-center">Д <br><small class="text-muted">Макс.: 12</small></span>
                            <span class="form-control dimensions-label text-center">Ш <br><small class="text-muted">Макс.: 2,5</small></span>
                            <span class="form-control dimensions-label text-center">В <br><small class="text-muted">Макс.: 2,5</small></span>
                            <span class="form-control dimensions-label text-center">Вес</span>
                            <span class="form-control dimensions-label text-center">Кол-во мест</span>
                        </div>
                        <div class="d-sm-none d-block">
                            <small class="text-muted">Длина макс.: 12.</small>
                            <small class="text-muted">Ширина макс.: 2,5.</small>
                            <small class="text-muted">Высота макс.: 2,5.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                @if(!empty(old('cargo.packages')))
                    @foreach(old('cargo.packages') as $key => $package)
                        <div class="row package-wrapper" id="package-wrapper-{{ $key }}">
                            <div class="col-11 form-item row package-item" id="package-{{ $key }}" data-package-id="{{ $key }}" style="padding-right: 0;">
                                <label class="col-sm-auto calc__label">
                                    <span class="content">Габариты каждого места (м)*</span>
                                </label>
                                <div class="col-sm col-12 calc__inpgrp relative row__inf"  style="padding-right: 0;">
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" max="12" id="packages_{{ $key }}_length" class="form-control text-center package-params package-dimensions" name="cargo[packages][{{ $key }}][length]" data-package-id="{{ $key }}" data-dimension-type="length" placeholder="Длина" value="{{ $package['length'] }}"/>
                                        <input type="number" min="0" step="any" max="2.5" id="packages_{{ $key }}_width" class="form-control text-center package-params package-dimensions" name="cargo[packages][{{ $key }}][width]" data-package-id="{{ $key }}"  data-dimension-type="width" placeholder="Ширина" value="{{ $package['width'] }}"/>
                                        <input type="number" min="0" step="any" max="2.5" id="packages_{{ $key }}_height" class="form-control text-center package-params package-dimensions" name="cargo[packages][{{ $key }}][height]" data-package-id="{{ $key }}"  data-dimension-type="height" placeholder="Высота" value="{{ $package['height'] }}"/>
                                        <input type="number" min="0" step="any" id="packages_{{ $key }}_weight" class="form-control text-center package-params package-weight" name="cargo[packages][{{ $key }}][weight]" data-package-id="{{ $key }}"  data-dimension-type="weight" placeholder="Вес" value="{{ $package['weight'] }}"/>
                                        <input type="number" min="0" step="any" id="packages_{{ $key }}_quantity" class="form-control text-center package-params package-quantity" name="cargo[packages][{{ $key }}][quantity]" data-package-id="{{ $key }}"  data-dimension-type="quantity" placeholder="Места" value="{{ $package['quantity'] }}"/>
                                    </div>
                                    <input type="number" step="any" hidden="hidden" id="packages_{{ $key }}_volume" class="form-control text-center package-params package-volume" name="cargo[packages][{{ $key }}][volume]" data-package-id="{{ $key }}"  data-dimension-type="volume" value="{{ $package['volume'] }}"/>
                                </div>
                            </div>
                            <a href="#" id="add-package-btn" class="col-1 align-self-sm-auto align-self-center add_anotherplace" title="Добавить">
                                <span class="badge calc_badge"><i class="fa fa-plus"></i> место</span>
                            </a>
                            <a href="#" id="delete-package-btn" class="col-1 align-self-sm-auto align-self-center add_anotherplace" title="Удалить">
                                <span class="badge calc_badge"><i class="fa fa-minus"></i></span>
                            </a>
                        </div>
                    @endforeach
                @else
                    @foreach($packages as $key => $package)
                        <div class="row package-wrapper" id="package-wrapper-{{ $key }}">
                            <div class="col-11 form-item row package-item" id="package-{{ $key }}" data-package-id="{{ $key }}" style="padding-right: 0;">
                                <label class="col-sm-auto calc__label">
                                    <span class="content">Габариты каждого места (м)*</span>
                                    @if($loop->first)
                                        <span class="d-md-none d-inline-block">(Д/Ш/В/Вес/Кол-во)</span>
                                        <div class="d-sm-none d-block">
                                            <small class="text-muted">Длина макс.: 12.</small>
                                            <small class="text-muted">Ширина макс.: 2,5.</small>
                                            <small class="text-muted">Высота макс.: 2,5.</small>
                                        </div>
                                    @endif
                                </label>
                                <div class="col-sm col-12 calc__inpgrp relative row__inf"  style="padding-right: 0;">
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" max="12" id="packages_{{ $key }}_length" class="form-control text-center package-params package-dimensions" name="cargo[packages][{{ $key }}][length]" data-package-id="{{ $key }}" data-dimension-type="length" placeholder="Длина" value="{{ $package['length'] }}"/>
                                        <input type="number" min="0" step="any" max="2.5" id="packages_{{ $key }}_width" class="form-control text-center package-params package-dimensions" name="cargo[packages][{{ $key }}][width]" data-package-id="{{ $key }}"  data-dimension-type="width" placeholder="Ширина" value="{{ $package['width'] }}"/>
                                        <input type="number" min="0" step="any" max="2.5" id="packages_{{ $key }}_height" class="form-control text-center package-params package-dimensions" name="cargo[packages][{{ $key }}][height]" data-package-id="{{ $key }}"  data-dimension-type="height" placeholder="Высота" value="{{ $package['height'] }}"/>
                                        <input type="number" min="0" step="any" id="packages_{{ $key }}_weight" class="form-control text-center package-params package-weight" name="cargo[packages][{{ $key }}][weight]" data-package-id="{{ $key }}"  data-dimension-type="weight" placeholder="Вес" value="{{ $package['weight'] }}"/>
                                        <input type="number" min="0" step="any" id="packages_{{ $key }}_quantity" class="form-control text-center package-params package-quantity" name="cargo[packages][{{ $key }}][quantity]" data-package-id="{{ $key }}"  data-dimension-type="quantity" placeholder="Места" value="{{ $package['quantity'] }}"/>
                                    </div>
                                    <input type="number" step="any" hidden="hidden" id="packages_{{ $key }}_volume" class="form-control text-center package-params package-volume" name="cargo[packages][{{ $key }}][volume]" data-package-id="{{ $key }}"  data-dimension-type="volume" value="{{ $package['volume'] }}"/>
                                </div>
                            </div>
                            <a href="#" id="add-package-btn" class="col-1 align-self-sm-auto align-self-center add_anotherplace" title="Добавить">
                                <span class="badge calc_badge"><i class="fa fa-plus"></i> место</span>
                            </a>
                            <a href="#" id="delete-package-btn" class="col-1 align-self-sm-auto align-self-center add_anotherplace" title="Удалить">
                                <span class="badge calc_badge"><i class="fa fa-minus"></i></span>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="row">
                <div class="col-6 form-item row align-items-center">
                    <label class="col-auto calc__label">Вес груза (общий, кг)*</label>
                    <div class="col calc__inpgrp">
                        <input type="number" min="0.01" name="cargo[total_weight]" step="any" id="total-weight" class="form-control" required value="{{ old('cargo[total_weight]') ?? ($totalWeight ?? 0) }}"/>
                    </div>
                </div>
                <div class="col-6 form-item row align-items-center text-right">
                    <label class="col-auto calc__label">Объем груза (общий, м<sup>3</sup>)*</label>
                    <div class="col calc__inpgrp">
                        <input type="number" min="0.01" name="cargo[total_volume]" step="any" id="total-volume" class="form-control" required data-total-volume="{{ old('cargo[total_volume]') ?? ($totalVolume ?? 0) }}" value="{{ old('cargo[total_volume]') ?? ($totalVolume ?? 0) }}"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 col-12">
                    <label>Если Ваш груз расположен на паллетах - введите количество:</label>
                </div>
                <div class="col-md-3 col-12">
                    <input type="number" min="0" name="cargo[total_quantity]" step="1" id="total-quantity" class="form-control" required data-total-quantity="{{ old('cargo[total_quantity]') ?? ($totalQuantity ?? 0) }}" value="{{ old('cargo[total_quantity]') ?? ($totalQuantity ?? 0) }}"/>
                </div>
            </div>
        </div>
        <div class="col-12">
            <p class="calc__info">Габариты груза влияют на расчет стоимости, без их указания стоимость может быть неточной</p>
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
                    maxlength="500"
                    class="form-control"
            >{{ old('cargo_comment') ?? ($order->cargo_comment ?? '') }}</textarea>
        </div>
    </div>
    <div class="form-item row block-for-distance mt-3">
        <label class="col-auto calc__label big">Откуда</label>
        <div class="col-md col-12 delivery-block">
            <div class="form-item">
                <select id="ship_city" class="form-control point-select" name="ship_city" placeholder="Выберите город" required>
                    <option value=""></option>
                    @if($shipCities->count() > 0)
                        @foreach($shipCities as $shipCity)
                            <option value="{{ $shipCity->name }}"
                                    data-data='{
                                        "terminal": "{{ $shipCity->coordinates_or_address }}",
                                        "kladrId": "{{ $shipCity->kladr->code ?? 'null' }}",
                                        "doorstep": "{{ $shipCity->doorstep }}",
                                        "doorstep_message": "{{ addcslashes($shipCity->doorstep_message->name ?? '', '"') }}"
                                    }'
                                    @if($selectedShipCity == $shipCity->id) selected @endif
                            >
                                {{ $shipCity->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-item">
                <a href="#" class="ajax-file-upload btn-sm btn-secondary mr-2">Загрузить схему проезда</a>
                <input type="file" id="take-file-input" class="d-none" accept="image/*"/>
                <div id="take-file-info" class="d-inline-block">
                    @if(!empty(old('take_driving_directions_file')) || !empty($order->take_driving_directions_file))
                        <a href="{{ url(old('take_driving_directions_file') ?? ($order->take_driving_directions_file ?? '')) }}" target='_blank'>Файл</a>
                        <a href='#' class='remove-file text-muted ml-2' data-type='take'><small>(Удалить)</small></a>
                    @endif
                </div>
                <input type="hidden" name="take_driving_directions_file" value="{{ old('take_driving_directions_file') ?? ($order->take_driving_directions_file ?? '') }}">
            </div>
            <div class="custom-control custom-checkbox">
                <input type="checkbox"
                       class="custom-control-input delivery-checkbox"
                       id="need-to-take"
                       name="need-to-take"
                       @if(!empty(old('need-to-take')) || (isset($order) && $order->take_need) || isset($deliveryPoint)) checked @endif>
                {{--<label class="custom-control-label" for="bring-your-own">Самостоятельно привезти груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                <label class="custom-control-label" for="need-to-take">Нужно забрать груз</label>
            </div>
            <div class="custom-control custom-radio d-none">
                <input type="radio" class="custom-control-input need-to-take-input delivery-type" id="need-to-take-type-in" name="need-to-take-type" value="in"
                        @if((!empty(old('need-to-take')) && old('need-to-take-type') == 'in') || (isset($order) && $order->take_need && $order->take_in_city)) checked @endif
                        @if(empty(old('need-to-take')) && !isset($order) && !isset($deliveryPoint) || (isset($order) && !$order->take_need)) disabled @endif/>
                <label class="custom-control-label" for="need-to-take-type-in">в пределах города отправления</label>
            </div>
            <div class="custom-control custom-radio d-none">
                <input type="radio"
                       class="custom-control-input need-to-take-input delivery-type"
                       id="need-to-take-type-from"
                       name="need-to-take-type"
                       value="from"
                       @if((!empty(old('need-to-take')) && old('need-to-take-type') == 'from') || (isset($order) && $order->take_need && !$order->take_in_city) || isset($deliveryPoint)) checked @endif
                       @if(empty(old('need-to-take')) && !isset($order) && !isset($deliveryPoint) && !isset($deliveryPoint) || (isset($order) && !$order->take_need)) disabled @endif
                />
                <label class="custom-control-label" for="need-to-take-type-from">из:</label>
            </div>

            <input type="hidden" name="take_city_name" value="{{ old('take_city_name') ?? ($order->take_city_name ?? ($deliveryPoint->name ?? '')) }}">
            <input type="hidden" name="take_distance" value="{{ old('take_distance') ?? ($order->take_distance ?? ($deliveryPoint->distance ?? 0)) }}" class="distance-hidden-input">
            <input type="hidden" name="take_polygon" value="{{ old('take_polygon') ?? ($order->take_polygon_id ?? '') }}" class="take-polygon-hidden-input">
            <div class="form-item ininner">
                <div class="relative">
                    <i class="dropdown-toggle fa-icon"></i>
                    <input class="form-control suggest_address need-to-take-input-address"
                           id="ship_point"
                           maxlength="150"
                           name="ship_point"
                           size="63"
                           type="text"
                           data-end="dest"
                           value="{{ old('ship_point') ?? ($order->take_address ?? ($deliveryPoint->name ?? '')) }}"
                           placeholder="Название населенного пункта или адрес"
                           data-name="{{ old('take_city_name') ?? ($order->take_city_name ?? ($deliveryPoint->city->name ?? '')) }}"
                           data-full-name="{{ old('ship_point') ?? ($order->take_address ?? ($deliveryPoint->name ?? '')) }}"
                           @if(empty(old('need-to-take')) && !isset($order) && !isset($deliveryPoint) || (isset($order) && !$order->take_need)) disabled @endif
                    >
                </div>
                {{--<div class="form-group-unlink"><a href="#" class="link-with-dotted">Выбрать отделение на карте</a></div>--}}
            </div>
            {{--<div class="custom-control custom-radio">--}}
            {{--<input type="radio" class="custom-control-input" id="pick-up-cargo" name="from" required>--}}
            {{--<label class="custom-control-label" for="pick-up-cargo">Забрать груз от адреса отправителя (1 050 <span class="rouble">p</span>)</label>--}}
            {{--</div>--}}
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input need-to-take-input x2-check" id="ship-from-point" name="ship-from-point"
                       @if((!empty(old('need-to-take')) && !empty(old('ship-from-point'))) || (isset($order) && $order->take_need && $order->take_point)) checked @endif
                       @if(empty(old('need-to-take')) && !isset($order) && !isset($deliveryPoint) || (isset($order) && !$order->take_need)) disabled @endif>
                {{--<label class="custom-control-label" for="you-can-pick">Самостоятельно забрать груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                <label class="custom-control-label" for="ship-from-point">Забор груза необходимо произвести в гипермаркете, распределительном центре или в точное время (временно́е "окно" менее 1 часа).</label>
            </div>
        </div>
    </div>
    <div class="form-item row block-for-distance">
        <label class="col-auto calc__label big">Куда</label>
        <div class="col-md col-12 delivery-block">
            <div class="form-item">
                {{--<input type="text" class="form-control" placeholder="email@example.com">--}}
                <select id="dest_city" class="form-control point-select" name="dest_city" placeholder="Выберите город" required>
                    <option value=""></option>
                    @if(isset($destinationCities))
                        @foreach($destinationCities as $destinationCity)
                            <option value="{{ $destinationCity->name }}"
                                    data-data='{
                                        "terminal": "{{ $destinationCity->coordinates_or_address }}",
                                        "kladrId": "{{ $destinationCity->kladr->code ?? 'null' }}",
                                        "doorstep": "{{ $destinationCity->doorstep }}",
                                        "doorstep_message": "{{ addcslashes($destinationCity->doorstep_message->name ?? '', '"') }}"
                                    }'
                                    @if($selectedDestCity == $destinationCity->id) selected @endif
                            >
                                {{ $destinationCity->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-item">
                <a href="#" class="ajax-file-upload btn-sm btn-secondary mr-2">Загрузить схему проезда</a>
                <input type="file" id="delivery-file-input" class="d-none" accept="image/*"/>
                <div id="delivery-file-info" class="d-inline-block">
                    @if(!empty(old('delivery_driving_directions_file')) || !empty($order->delivery_driving_directions_file))
                        <a href="{{ url(old('delivery_driving_directions_file') ?? ($order->delivery_driving_directions_file ?? '')) }}" target='_blank'>Файл</a>
                        <a href='#' class='remove-file text-muted ml-2' data-type='delivery'><small>(Удалить)</small></a>
                    @endif
                </div>
                <input type="hidden" name="delivery_driving_directions_file" value="{{ old('delivery_driving_directions_file') ?? ($order->delivery_driving_directions_file ?? '') }}">
            </div>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input delivery-checkbox" id="need-to-bring" name="need-to-bring"
                       @if(!empty(old('need-to-bring')) || (isset($order) && $order->delivery_need || isset($bringPoint))) checked @endif
                >
                {{--<label class="custom-control-label" for="you-can-pick">Самостоятельно забрать груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                <label class="custom-control-label" for="need-to-bring">Нужно доставить груз</label>
            </div>
            <div class="custom-control custom-radio d-none">
                <input type="radio" class="custom-control-input need-to-bring-input delivery-type" id="need-to-bring-type-in" name="need-to-bring-type" value="in"
                       @if((!empty(old('need-to-bring')) && old('need-to-bring-type') == 'in') || (isset($order) && $order->delivery_need && $order->delivery_in_city)) checked @endif
                       @if(empty(old('need-to-bring')) && (!isset($order) && !isset($bringPoint) || (isset($order) && !$order->delivery_need))) disabled @endif/>
                <label class="custom-control-label" for="need-to-bring-type-in">в пределах города назначения</label>
            </div>
            <div class="custom-control custom-radio d-none">
                <input type="radio" class="custom-control-input need-to-bring-input delivery-type" id="need-to-bring-type-from" name="need-to-bring-type" value="from"
                       @if((!empty(old('need-to-bring')) && old('need-to-bring-type') == 'from') || (isset($order) && $order->delivery_need && !$order->delivery_in_city || isset($bringPoint))) checked @endif
                       @if(empty(old('need-to-bring')) && (!isset($order) && !isset($bringPoint) || (isset($order) && !$order->delivery_need))) disabled @endif/>
                <label class="custom-control-label" for="need-to-bring-type-from">в:</label>
            </div>

            <input type="hidden" name="bring_city_name" value="{{ old('bring_city_name') ?? ($order->delivery_city_name ?? ($bringPoint->name ?? '')) }}">
            <input type="hidden" name="bring_distance" value="{{ old('bring_distance') ?? ($order->delivery_distance ?? ($bringPoint->distance ?? 0)) }}" class="distance-hidden-input">
            <input type="hidden" name="bring_polygon" value="{{ old('bring_polygon') ?? ($order->bring_polygon_id ?? 0) }}" class="bring-polygon-hidden-input">
            <div class="form-item ininner">
                <div class="relative">
                    <i class="dropdown-toggle fa-icon"></i>
                    <input class="form-control suggest_address need-to-bring-input-address"
                           id="dest_point"
                           name="dest_point"
                           maxlength="150"
                           value="{{ old('dest_point') ?? ($order->delivery_address ?? ($bringPoint->name ?? '')) }}"
                           placeholder="Название населенного пункта или адрес"
                           @if(empty(old('need-to-bring')) && !isset($order) && !isset($bringPoint) || (isset($order) && !$order->delivery_need)) disabled @endif
                           data-name="{{ old('bring_city_name') ?? ($order->delivery_city_name ?? ($bringPoint->city->name ?? '')) }}"
                           data-full-name="{{ old('dest_point') ?? ($order->delivery_address ?? ($bringPoint->name ?? '')) }}"
                    >
                </div>
                {{--<div class="form-group-unlink"><a href="#" class="link-with-dotted">Выбрать отделение на карте</a></div>--}}
            </div>
            {{--<div class="custom-control custom-radio">--}}
            {{--<input type="radio" class="custom-control-input" id="deliver-cargo" name="where" required>--}}
            {{--<label class="custom-control-label" for="deliver-cargo">Доставить груз до адреса получателя (1 050 <span class="rouble">p</span>)</label>--}}
            {{--</div>--}}
            <div class="custom-control custom-checkbox">
                <input type="checkbox"
                       class="custom-control-input need-to-bring-input x2-check"
                       id="bring-to-point"
                       name="bring-to-point"
                       @if((!empty(old('need-to-bring')) && !empty(old('bring-to-point'))) || (isset($order) && $order->delivery_need && $order->delivery_point)) checked @endif
                       @if(empty(old('need-to-bring')) && !isset($order) && !isset($bringPoint) || (isset($order) && !$order->delivery_need)) disabled @endif
                >
                {{--<label class="custom-control-label" for="you-can-pick">Самостоятельно забрать груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                <label class="custom-control-label" for="bring-to-point">Доставку груза необходимо произвести в гипермаркет, распределительный центр или в точное время (временно́е "окно" менее 1 часа).</label>
            </div>
        </div>
    </div>
    <div class="calc__title">Дополнительные услуги</div>
    {{--<div class="form-item form-group-additional">--}}
    {{--<div class="relative">--}}
    {{--<i class="dropdown-toggle fa-icon"></i>--}}
    {{--<select class="custom-select">--}}
    {{--<option disabled selected>Упаковка</option>--}}
    {{--<option>1</option>--}}
    {{--<option>2</option>--}}
    {{--<option>3</option>--}}
    {{--<option>4</option>--}}
    {{--<option>5</option>--}}
    {{--</select>--}}
    {{--</div>--}}
    {{--</div>--}}
    <div class="form-item form-group-additional">
        @foreach($services as $service)
            <div class="custom-control custom-checkbox">
                <input type="checkbox"
                       class="custom-control-input custom-service-checkbox"
                       name="service[]"
                       id="{{ $service->slug }}"
                       value="{{ $service->id }}"
                       @if(empty(old('service')) && (isset($order) && in_array($service->id, $order->order_services->pluck('id')->toArray())))
                            checked
                       @elseif(!empty(old('service')) && in_array($service->id, old('service')))
                            checked
                       @endif
                >
                <label class="custom-control-label" for="{{ $service->slug }}">{{ $service->description }}</label>
            </div>
        @endforeach
    </div>
    <div class="form-item form-group-additional">
        @php
            $insuranceNeed = true;
            if(empty(old())) {
                if(isset($order) && empty($order->insurance)) {
                    $insuranceNeed = false;
                }
            } else {
                $insuranceNeed = !empty(old('insurance'));
            }
        @endphp
        <div class="custom-control custom-checkbox">
            <input type="checkbox"
                   name="insurance"
                   class="custom-control-input"
                   id="insurance"
                   {{ $insuranceNeed ? 'checked' : '' }}
            >
            <label class="custom-control-label" for="insurance">Страхование</label>
        </div>
        <div id="insurance-amount-wrapper"
             style="{{ $insuranceNeed ? '' : 'display: none;' }}"
        >
            <br>
            <label class="" for="insurance-amount">Сумма страховки</label>
            <input type="number"
                   class="form-control"
                   id="insurance-amount"
                   name="insurance_amount"
                   placeholder="Введите сумму страховки"
                   value="{{ !empty(old('insurance_amount')) ? old('insurance_amount') : ($order->insurance ?? '50000') }}"
                   {{ $insuranceNeed ? 'min="50000" required' : '' }}
            >
            <br>
        </div>
        <div class="relative">
            <label class="" for="discount">Скидка (%)</label>
            <input type="number"
                   class="form-control"
                   id="discount"
                   name="discount"
                   value="{{ $order->discount ?? 0 }}"
                   min="0"
                   max="100"
                   @if(\Illuminate\Support\Facades\Auth::check()) readonly @endif
                   required>
        </div>

        {{--<div class="custom-control custom-checkbox">--}}
        {{--<input type="checkbox" class="custom-control-input" id="loading-and-unloading">--}}
        {{--<label class="custom-control-label" for="loading-and-unloading">Погрузо-разгрузочные работы</label>--}}
        {{--</div>--}}
        {{--<div class="custom-control custom-checkbox">--}}
        {{--<input type="checkbox" class="custom-control-input" id="exact-time">--}}
        {{--<label class="custom-control-label" for="exact-time">Доставка к точному времен</label>--}}
        {{--</div>--}}
        {{--<div class="custom-control custom-checkbox">--}}
        {{--<input type="checkbox" class="custom-control-input" id="date-execution">--}}
        {{--<label class="custom-control-label" for="date-execution">Дата исполнение заказа</label>--}}
        {{--</div>--}}
    </div>
    {{--<div class="form-item ininner">--}}
    {{--<div class="row align-items-center">--}}
    {{--<div class="col-4">--}}
    {{--<div class="relative">--}}
    {{--<i class="fa-icon fa fa-calendar"></i>--}}
    {{--<input type="text" class="form-control" placeholder="12.01.2019">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<span class="annotation-text">дата приезда машины к отправителю</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-item ininner">--}}
    {{--<div class="row align-items-center">--}}
    {{--<div class="input-group col-4">--}}
    {{--<input type="text" class="form-control text-center" placeholder="09:00" />--}}
    {{--<i class="group-input__icon fa fa-minus"></i>--}}
    {{--<input type="text" class="form-control text-center" placeholder="17:00" />--}}
    {{--</div>--}}
    {{--<span class="annotation-text">время забора</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    <div class="calc__title">Отправитель</div>
    @foreach($userTypes as $userType)
        <div class="custom-control custom-radio">
            <input type="radio"
                   data-slug="{{ $userType->slug }}"
                   class="custom-control-input"
                    @if(!empty(old('sender_type_id')))
                        @if(old('sender_type_id') == $userType->id) checked @endif
                    @else
                        @if(isset($order->sender_type) && $order->sender_type_id == $userType->id) checked @endif
                    @endif
                   id="sender_type_{{ $userType->slug }}"
                   value="{{ $userType->id }}"
                   name="sender_type_id"
                   required
            />
            <label class="custom-control-label" for="sender_type_{{ $userType->slug }}">{{ $userType->name }}</label>
        </div>
    @endforeach
    <div class="sender-forms">
        <div class="legal"
             @if(!empty(old('sender_type_id')) && !empty($userTypes->where('id', old('sender_type_id'))->first()))
                 @if($userTypes->where('id', old('sender_type_id'))->first()->slug == 'yuridicheskoe-lico')
                    style="display: block"
                 @else
                    style="display: none"
                 @endif
             @else
                 @if(isset($order->sender_type) && $order->sender_type->slug == 'yuridicheskoe-lico')
                    style="display: block"
                 @else
                    style="display: none"
                @endif
             @endif
        >
            @include('v1.partials.calculator.sender-forms.legal-type-form')
        </div>
        <div class="individual"
             @if(!empty(old('sender_type_id')) && !empty($userTypes->where('id', old('sender_type_id'))->first()))
                 @if($userTypes->where('id', old('sender_type_id'))->first()->slug == 'fizicheskoe-lico')
                    style="display: block"
                 @else
                    style="display: none"
                 @endif
             @else
                 @if(isset($order->sender_type) && $order->sender_type->slug == 'fizicheskoe-lico')
                    style="display: block"
                 @else
                    style="display: none"
                 @endif
             @endif
        >
            @include('v1.partials.calculator.sender-forms.individual-type-form')
        </div>
    </div>

    <div class="calc__title">Получатель</div>
    @foreach($userTypes as $userType)
        <div class="custom-control custom-radio">
            <input type="radio"
                   data-slug="{{ $userType->slug }}"
                   class="custom-control-input"
                   id="recipient_type_{{ $userType->slug }}"
                   @if(!empty(old('recipient_type_id')))
                        @if(old('recipient_type_id') == $userType->id) checked @endif
                   @else
                        @if(isset($order->recipient_type) && $order->recipient_type_id == $userType->id) checked @endif
                   @endif
                   value="{{ $userType->id }}"
                   name="recipient_type_id"
                   required
            />
            <label class="custom-control-label" for="recipient_type_{{ $userType->slug }}">{{ $userType->name }}</label>
        </div>
    @endforeach
    <div class="recipient-forms">
        <div class="legal"
             @if(!empty(old('recipient_type_id')) && !empty($userTypes->where('id', old('recipient_type_id'))->first()))
                    @if($userTypes->where('id', old('recipient_type_id'))->first()->slug == 'yuridicheskoe-lico')
                        style="display: block"
                    @else
                        style="display: none"
                    @endif
             @else
                    @if(isset($order->recipient_type) && $order->recipient_type->slug == 'yuridicheskoe-lico')
                        style="display: block"
                    @else
                        style="display: none"
                    @endif
             @endif
        >
            @include('v1.partials.calculator.recipient-forms.legal-type-form')
        </div>
        <div class="individual"
             @if(!empty(old('recipient_type_id')) && !empty($userTypes->where('id', old('recipient_type_id'))->first()))
                 @if($userTypes->where('id', old('recipient_type_id'))->first()->slug == 'fizicheskoe-lico')
                    style="display: block"
                 @else
                    style="display: none"
                 @endif
             @else
                 @if(isset($order->recipient_type) && $order->recipient_type->slug == 'fizicheskoe-lico')
                    style="display: block"
                 @else
                    style="display: none"
                 @endif
             @endif
        >
            @include('v1.partials.calculator.recipient-forms.individual-type-form')
        </div>
    </div>

    <div class="calc__title">Данные плательщика</div>
    <div class="custom-control custom-radio">
        <input type="radio"
               @if(!empty(old('payer_type')))
                    @if(old('payer_type') === 'otpravitel') checked @endif
               @else
                    @if(isset($order->payer) && $order->payer->slug === 'otpravitel') checked @endif
               @endif
               class="custom-control-input"
               id="sender"
               name="payer_type"
               value="otpravitel"
               required
        />
        <label class="custom-control-label" for="sender">Отправитель</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio"
               @if(!empty(old('payer_type')))
                    @if(old('payer_type') === 'poluchatel') checked @endif
               @else
                    @if(isset($order->payer) && $order->payer->slug === 'poluchatel') checked @endif
               @endif
               class="custom-control-input"
               id="recipient"
               name="payer_type"
               value="poluchatel"
               required
        />
        <label class="custom-control-label" for="recipient">Получатель</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio"
               @if(!empty(old('payer_type')))
                    @if(old('payer_type') === '3-e-lico') checked @endif
               @else
                    @if(isset($order->payer) && $order->payer->slug === '3-e-lico') checked @endif
               @endif
               class="custom-control-input"
               id="3rd-person"
               name="payer_type"
               value="3-e-lico"
               required
        />
        <label class="custom-control-label" for="3rd-person">3-е лицо</label>
    </div>
    <div id="3rd-person-payer"
         @if(!empty(old('payer_type')))
            @if(old('payer_type') !== '3-e-lico') style="display: none" @endif
         @else
            @if(!isset($order->payer) || $order->payer->slug !== '3-e-lico') style="display: none" @endif
         @endif
    >
        @foreach($userTypes as $userType)
            <div class="custom-control custom-radio">
                <input type="radio"
                       data-slug="{{ $userType->slug }}"
                       class="custom-control-input req"
                       id="payer_type_{{ $userType->slug }}"
                       @if(!empty(old('payer_form_type_id')))
                            @if(old('payer_form_type_id') == $userType->id) checked @endif
                       @else
                            @if(isset($order->payer_form_type) && $order->payer_form_type_id == $userType->id) checked @endif
                       @endif
                       value="{{ $userType->id }}"
                       name="payer_form_type_id"
                       required
                />
                <label class="custom-control-label" for="payer_type_{{ $userType->slug }}">{{ $userType->name }}</label>
            </div>
        @endforeach
        <div class="payer-forms">
            <div class="legal"
                 @if(!empty(old('payer_form_type_id')) && !empty($userTypes->where('id', old('payer_form_type_id'))->first()))
                     @if($userTypes->where('id', old('payer_form_type_id'))->first()->slug == 'yuridicheskoe-lico')
                         style="display: block"
                     @else
                         style="display: none"
                     @endif
                 @else
                     @if(isset($order->payer_form_type) && $order->payer_form_type->slug == 'yuridicheskoe-lico')
                        style="display: block"
                     @else
                        style="display: none"
                     @endif
                 @endif
            >
                @include('v1.partials.calculator.payer-forms.legal-type-form')
            </div>
            <div class="individual"
                 @if(!empty(old('payer_form_type_id')) && !empty($userTypes->where('id', old('payer_form_type_id'))->first()))
                     @if($userTypes->where('id', old('payer_form_type_id'))->first()->slug == 'fizicheskoe-lico')
                        style="display: block"
                     @else
                        style="display: none"
                     @endif
                 @else
                     @if(isset($order->payer_form_type) && $order->payer_form_type->slug == 'fizicheskoe-lico')
                        style="display: block"
                     @else
                        style="display: none"
                     @endif
                 @endif
            >
                @include('v1.partials.calculator.payer-forms.individual-type-form')
            </div>
        </div>
    </div>

    <div class="calc__title">Форма оплаты</div>
    <div class="custom-control custom-radio">
        <input type="radio"
               class="custom-control-input"
               id="available"
               value="nalichnyy-raschet"
               @if(!empty(old('payment')))
                    @if(old('payment') === 'nalichnyy-raschet') checked @endif
               @else
                    @if(isset($order) && $order->payment->slug === 'nalichnyy-raschet') checked @endif
               @endif
               name="payment"
               required
        />
        <label class="custom-control-label" for="available">Наличный расчет</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio"
               class="custom-control-input"
               id="non-cash"
               value="beznalichnyy-raschet"
               @if(!empty(old('payment')))
                    @if(old('payment') === 'beznalichnyy-raschet') checked @endif
               @else
                    @if(isset($order) && $order->payment->slug === 'beznalichnyy-raschet') checked @endif
               @endif
               name="payment"
               required
        />
        <label class="custom-control-label" for="non-cash">Безналичный расчет</label>
    </div>
    <div class="calc__title">Заявку заполнил</div>
    <div id="order-creator-wrapper">
        <div class="mb-3">
            <label class="" for="order-creator">ФИО</label>
            <input type="text"
                   class="form-control"
                   id="order-creator"
                   name="order-creator"
                   placeholder="ФИО"
                   value="{{ old('order-creator') ?? ($order->order_creator ?? (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->full_name : '')) }}"
                   required>
        </div>
        <div class="mb-3" id="payer-email-wrapper">
            <label class="" for="payer-email">E-Mail</label>
            <input type="email"
                   class="form-control"
                   id="payer-email"
                   name="payer-email"
                   placeholder="Введите E-Mail"
                   value="{{ old('payer-email') ?? ($order->payer_email ?? '') }}"
                   required
            >
        </div>
    </div>
    <div>
        <label class="mb-0" for="">Тип</label>
        <div class="custom-control custom-radio">
            <input type="radio"
                   class="custom-control-input"
                   id="order-creator-type-sender"
                   value="otpravitel-1"
                   name="order-creator-type"
                   @if(!empty(old('order-creator-type')))
                        @if(old('order-creator-type') === 'otpravitel-1')
                            checked
                        @endif
                   @else
                       @if(isset($order->order_creator_type_model->slug) && $order->order_creator_type_model->slug === 'otpravitel-1')
                            checked
                       @endif
                   @endif
                   required
            />
            <label class="custom-control-label" for="order-creator-type-sender">Отправитель</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio"
                   class="custom-control-input"
                   id="order-creator-type-recipient"
                   value="poluchatel-1"
                   name="order-creator-type"
                   @if(!empty(old('order-creator-type')))
                       @if(old('order-creator-type') === 'poluchatel-1')
                            checked
                       @endif
                   @else
                       @if(isset($order->order_creator_type_model->slug) && $order->order_creator_type_model->slug === 'poluchatel-1')
                            checked
                       @endif
                   @endif
                   required
            />
            <label class="custom-control-label" for="order-creator-type-recipient">Получатель</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio"
                   class="custom-control-input"
                   id="order-creator-type-payer"
                   value="platelshchik"
                   name="order-creator-type"
                   @if(!empty(old('order-creator-type')))
                       @if(old('order-creator-type') === 'platelshchik')
                            checked
                       @endif
                   @else
                       @if(isset($order->order_creator_type_model->slug) && $order->order_creator_type_model->slug === 'platelshchik')
                            checked
                       @endif
                   @endif
                   required
            />
            <label class="custom-control-label" for="order-creator-type-payer">Плательщик</label>
        </div>
    </div>
    <div class="form-item my-5">
        <div class="g-recaptcha" data-sitekey="{{ env('V2_GOOGLE_CAPTCHA_KEY') }}"></div>
        @if ($errors->has('g-recaptcha-response'))
            <div>
                <span class="invalid-feedback" role="alert" style="display: block">
                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                </span>
            </div>
        @endif
    </div>
    @if($orderType === 'order')
        <div>
            <h2 class="text-center">ВНИМАНИЕ!</h2>
            <hr style="margin: 2px 0; border: solid 1px">
            <div>
                <p class="text-center mb-2">
                    ЗАЯВКИ  ПРИНИМАЮТСЯ с 9.00 до 16.00 за сутки до исполнения.
                </p>
            </div>
            <div>
                <p class="text-center mb-2">
                    Заявки, поступившие после 16.00 выполняются по мере возможности.
                </p>
            </div>
            <div>
                <p class="text-center">
                    ВОДИТЕЛЬ-ЭКСПЕДИТОР НЕ ЗАНИМАЕТСЯ ПОГРУЗОЧНО-РАЗГРУЗОЧНЫМИ РАБОТАМИ!!
                </p>
            </div>
            <ul class="mb-3 ml-3 list-style-dist">
                <li>доставка осуществляется только до адреса. Погрузка-выгрузка и поднятие на этаж - возможны по предварительному согласованию с менеджером направления;</li>
                <li>исполнение заявки в течении рабочего дня (с 10.00-18.00) , указание конкретного времени подачи машины осложняет  выполнение заявки, стоимость может увеличиться до 100%;</li>
                <li>погрузка-выгрузка а/м 30 минут, простой каждого последующего часа оплачивается по прайс-листу;</li>
                <li>неправильно указанные координаты (адрес, телефон),  прогон а/м, отказ от заявки по факту подачи а/м  - считается выполненной заявкой и оплачивается по прайс-листу;</li>
                <li>оплата въезда на территорию клиента – оплачивается дополнительно;</li>
                <li>стоимость  дополнительной  упаковки  указана без стоимости поддона (200 руб.);</li>
                <li>областная доставка расчитывается по формуле: авто доставка по СПб + пробег * руб./км в оба конца, согласно прайс-листа;</li>
            </ul>
            <div>
                <p class="text-center">
                    Обращаем внимание на необходимость указания Вашего электронного адреса. На указанный Вами электронный адрес в течение часа поступит информация о принятии данной Заявки к исполнению.
                </p>
            </div>
        </div>
    @endif
    <div class="form-item d-flex flex-column flex-md-row">
        @if(\Illuminate\Support\Facades\Auth::check())
            <button type="submit" name="status" value="chernovik" class="btn margin-item btn-default my-2 my-lg-0">Сохранить черновик</button>
        @endif
        <button type="submit" name="status" value="order_auth" class="btn margin-item btn-danger my-2 my-lg-0">Оформить заявку через личный кабинет</button>
        @if(\Illuminate\Support\Facades\Auth::guest())
            <button type="submit" name="status" value="order_guest" class="btn margin-item btn-danger my-2 my-lg-0">Оформить заявку без регистрации</button>
        @endif
    </div>
    @csrf
</form>
