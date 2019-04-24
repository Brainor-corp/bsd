<form class="calculator-form" action="{{ route('order-save-action') }}" method="post">
    @csrf
    <div class="calc__title">Груз</div>
    <div class="form-item row align-items-center">
        <label class="col-auto calc__label">Наименование груза*</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Введите наименование груза" name="cargo[name]" @if(isset($package['name'])) value="{{ $package['name'] }}" @endif required>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-11 form-item row align-items-center" style="padding-right: 0;">
                    <label class="col-auto calc__label"></label>
                    <div class="col calc__inpgrp relative row__inf"  style="padding-right: 0;">
                        <div class="input-group">
                            <span class="form-control dimensions-label text-center">Д</span>
                            <span class="form-control dimensions-label text-center">Ш</span>
                            <span class="form-control dimensions-label text-center">В</span>
                            <span class="form-control dimensions-label text-center">Вес</span>
                            <span class="form-control dimensions-label text-center">Кол-во</span>
                        </div>
                    </div>
                </div>
                @if(isset($packages))
                    @foreach($packages as $key=>$package)
                        <div class="col-11 form-item row align-items-center package-item" id="package-{{ $key }}" data-package-id="{{ $key }}" style="padding-right: 0;">
                            <label class="col-auto calc__label">Габариты (м)*</label>
                            <div class="col calc__inpgrp relative row__inf"  style="padding-right: 0;">
                                <div class="input-group">
                                    <input type="text" id="packages_{{ $key }}_length" class="form-control text-center package-params package-dimensions" name="cargo[packages][{{ $key }}][length]" data-package-id="{{ $key }}" data-dimension-type="length" placeholder="Длина" @if(isset($package['length'])) value="{{ $package['length'] }}" @endif/>
                                    <input type="text" id="packages_{{ $key }}_width" class="form-control text-center package-params package-dimensions" name="cargo[packages][{{ $key }}][width]" data-package-id="{{ $key }}"  data-dimension-type="width" placeholder="Ширина" @if(isset($package['width'])) value="{{ $package['width'] }}" @endif/>
                                    <input type="text" id="packages_{{ $key }}_height" class="form-control text-center package-params package-dimensions" name="cargo[packages][{{ $key }}][height]" data-package-id="{{ $key }}"  data-dimension-type="height" placeholder="Высота" @if(isset($package['height'])) value="{{ $package['height'] }}" @endif/>
                                    <input type="text" id="packages_{{ $key }}_weight" class="form-control text-center package-params package-weight" name="cargo[packages][{{ $key }}][weight]" data-package-id="{{ $key }}"  data-dimension-type="weight" placeholder="Вес" @if(isset($package['weight'])) value="{{ $package['weight'] }}" @endif/>
                                    <input type="text" id="packages_{{ $key }}_quantity" class="form-control text-center package-params package-quantity" name="cargo[packages][{{ $key }}][quantity]" data-package-id="{{ $key }}"  data-dimension-type="quantity" placeholder="Места" @if(isset($package['quantity'])) value="{{ $package['quantity'] }}" @endif/>
                                </div>
                                <input type="text" hidden="hidden" id="packages_{{ $key }}_volume" class="form-control text-center package-params package-volume" name="cargo[packages][{{ $key }}][volume]" data-package-id="{{ $key }}"  data-dimension-type="volume" @if(isset($package['volume'])) value="{{ $package['volume'] }}" @endif/>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-11 form-item row align-items-center package-item" id="package-{{ $key }}" data-package-id="{{ $key }}" style="padding-right: 0;">
                        <label class="col-auto calc__label">Габариты (м)*</label>
                        <div class="col calc__inpgrp relative row__inf"  style="padding-right: 0;">
                            <div class="input-group">
                                <input type="text" id="packages_1_length" class="form-control text-center package-params package-dimensions" name="cargo[packages][1][length]" data-package-id="1" data-dimension-type="length" placeholder="Длина" value="0.1"/>
                                <input type="text" id="packages_1_width" class="form-control text-center package-params package-dimensions" name="cargo[packages][1][width]" data-package-id="1"  data-dimension-type="width" placeholder="Ширина" value="0.1"/>
                                <input type="text" id="packages_1_height" class="form-control text-center package-params package-dimensions" name="cargo[packages][1][height]" data-package-id="1"  data-dimension-type="height" placeholder="Высота" value="0.1"/>
                                <input type="text" id="packages_1_weight" class="form-control text-center package-params package-weight" name="cargo[packages][1][weight]" data-package-id="1"  data-dimension-type="weight" placeholder="Вес" value="1"/>
                                <input type="text" id="packages_1_quantity" class="form-control text-center package-params package-quantity" name="cargo[packages][1][quantity]" data-package-id="1"  data-dimension-type="quantity" placeholder="Места" value="1"/>
                            </div>
                            <input type="text" hidden="hidden" id="packages_1_volume" class="form-control text-center package-params package-volume" name="cargo[packages][1][volume]" data-package-id="1"  data-dimension-type="volume" value="0.01"/>
                        </div>
                    </div>
                @endif
                <a href="#" id="add-package-btn" class=" col-1 add_anotherplace">
                    <span class="badge calc_badge"><i class="fa fa-plus"></i></span>
                </a>
            </div>

            <div class="row">
                <div class="col-6 form-item row align-items-center">
                    <label class="col-auto calc__label">Вес груза (кг)*</label>
                    <div class="col calc__inpgrp">
                        <input type="text" id="total-weight-hidden" hidden="hidden" style="display: none" name="cargo[total_weight]" data-total-volume="1" value="1"/>
                        <input type="text" id="total-weight" class="form-control" value="1" required/>
                    </div>
                </div>
                <div class="col-6 form-item row align-items-center text-right">
                    <label class="col-auto calc__label">Объем (м<sup>3</sup>)*</label>
                    <div class="col calc__inpgrp">
                        <input type="text" id="total-volume-hidden" hidden="hidden" name="cargo[total_volume]" data-total-volume="0.01" value="0.01" required/>
                        <input type="text" id="total-volume" class="form-control" value="0.01" />
                    </div>
                </div>
            </div>

        </div>
        <div class="col-12">
            <p class="calc__info">Габариты груза влияют на расчет стоимости, без их указания стоимость может быть неточной</p>
        </div>
    </div>
    <div class="form-item row block-for-distance">
        <label class="col-auto calc__label big">Откуда</label>
        <div class="col delivery-block">
            <div class="form-item">
                {{--<input type="text" class="form-control" placeholder="email@example.com">--}}
                <select id="ship_city" class="form-control point-select" name="ship_city" placeholder="Москва">
                    <option value=""></option>
                    @if($shipCities->count() > 0)
                        @foreach($shipCities as $shipCity)
                            <option value="{{ $shipCity->id }}" data-terminal="{{ $destinationCity->terminal->address ?? '' }}" @if($selectedShipCity == $shipCity->id) selected @endif>{{ $shipCity->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input delivery-checkbox" id="need-to-take" name="need-to-take">
                {{--<label class="custom-control-label" for="bring-your-own">Самостоятельно привезти груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                <label class="custom-control-label" for="need-to-take">Нужно забрать груз</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" checked class="custom-control-input need-to-take-input" id="need-to-take-type-in" name="need-to-take-type" value="in" disabled/>
                <label class="custom-control-label" for="need-to-take-type-in">в пределах города отправления</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input need-to-take-input" id="need-to-take-type-from" name="need-to-take-type" value="from" disabled/>
                <label class="custom-control-label" for="need-to-take-type-from">из:</label>
            </div>

            <div class="form-item ininner">
                <div class="relative">
                    <i class="dropdown-toggle fa-icon"></i>
                    <input class="form-control suggest_address need-to-take-input-address" id="ship_point" maxlength="256" name="ship_point" size="63" type="text" data-end="dest" placeholder="Название населенного пункта или адрес"  disabled>
                </div>
                {{--<div class="form-group-unlink"><a href="#" class="link-with-dotted">Выбрать отделение на карте</a></div>--}}
            </div>
            {{--<div class="custom-control custom-radio">--}}
            {{--<input type="radio" class="custom-control-input" id="pick-up-cargo" name="from" required>--}}
            {{--<label class="custom-control-label" for="pick-up-cargo">Забрать груз от адреса отправителя (1 050 <span class="rouble">p</span>)</label>--}}
            {{--</div>--}}
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input need-to-take-input x2-check" id="ship-from-point" name="ship-from-point" disabled>
                {{--<label class="custom-control-label" for="you-can-pick">Самостоятельно забрать груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                <label class="custom-control-label" for="ship-from-point">Доставку груза необходимо произвести в гипермаркете, распределительном центре или в точное время (временно́е "окно" менее 1 часа).</label>
            </div>
        </div>
    </div>
    <div class="form-item row block-for-distance">
        <label class="col-auto calc__label big">Куда</label>
        <div class="col delivery-block">
            <div class="form-item">
                {{--<input type="text" class="form-control" placeholder="email@example.com">--}}
                <select id="dest_city" class="form-control point-select" name="dest_city" placeholder="Москва">
                    <option value=""></option>
                    @if(isset($destinationCities))
                        @foreach($destinationCities as $destinationCity)
                            <option value="{{ $destinationCity->id }}" data-terminal="{{ $destinationCity->terminal->address ?? '' }}" @if($selectedDestCity == $destinationCity->id) selected @endif>{{ $destinationCity->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input delivery-checkbox" id="need-to-bring" name="need-to-bring">
                {{--<label class="custom-control-label" for="you-can-pick">Самостоятельно забрать груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                <label class="custom-control-label" for="need-to-bring">Нужно доставить груз</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" checked class="custom-control-input need-to-bring-input" id="need-to-bring-type-in" name="need-to-bring-type" value="in" disabled/>
                <label class="custom-control-label" for="need-to-bring-type-in">в пределах города отправления</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input need-to-bring-input" id="need-to-bring-type-from" name="need-to-bring-type" value="from" disabled/>
                <label class="custom-control-label" for="need-to-bring-type-from">в:</label>
            </div>
            <div class="form-item ininner">
                <div class="relative">
                    <i class="dropdown-toggle fa-icon"></i>
                    <input class="form-control suggest_address need-to-bring-input-address"  id="dest_point" name="dest_point" placeholder="Название населенного пункта или адрес" disabled>
                </div>
                {{--<div class="form-group-unlink"><a href="#" class="link-with-dotted">Выбрать отделение на карте</a></div>--}}
            </div>
            {{--<div class="custom-control custom-radio">--}}
            {{--<input type="radio" class="custom-control-input" id="deliver-cargo" name="where" required>--}}
            {{--<label class="custom-control-label" for="deliver-cargo">Доставить груз до адреса получателя (1 050 <span class="rouble">p</span>)</label>--}}
            {{--</div>--}}
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input need-to-bring-input x2-check" id="bring-to-point" name="bring-to-point" disabled>
                {{--<label class="custom-control-label" for="you-can-pick">Самостоятельно забрать груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                <label class="custom-control-label" for="bring-to-point">Забор груза необходимо произвести в гипермаркете, распределительном центре или в точное время (временно́е "окно" менее 1 часа).</label>
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
                <input type="checkbox" class="custom-control-input custom-service-checkbox" name="service[]" id="{{ $service->slug }}" value="{{ $service->id }}">
                <label class="custom-control-label" for="{{ $service->slug }}">{{ $service->description }}</label>
            </div>
        @endforeach
    </div>
    <div class="form-item form-group-additional">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="insurance" disabled checked>
            <label class="custom-control-label" for="insurance">Страхование</label>
        </div>
        <div id="insurance-amount-wrapper">
            <br>
            <label class="" for="insurance-amount">Сумма страховки</label>
            <input type="text" class="form-control" id="insurance-amount" name="insurance_amount" placeholder="Введите сумму страховки" value="50000">
            <br>
        </div>
        <div class="relative">
            <i class="dropdown-toggle fa-icon"></i>
            <select class="custom-select" id="discount" name="discount">
                <option disabled selected>У меня есть скидка</option>
                <option value="3">3%</option>
                <option value="5">5%</option>
                <option value="10">10%</option>
                <option value="15">15%</option>
                <option value="20">20%</option>
            </select>
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
    {{--<div class="custom-control custom-radio">--}}
    {{--<input type="radio" class="custom-control-input" id="sender-legally" name="sender" required />--}}
    {{--<label class="custom-control-label" for="sender-legally">Юридическое лицо</label>--}}
    {{--</div>--}}
    {{--<div class="custom-control custom-radio">--}}
    {{--<input type="radio" class="custom-control-input" id="sender-private" name="sender" required />--}}
    {{--<label class="custom-control-label" for="sender-private">Физическое лицо</label>--}}
    {{--</div>--}}
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">Правовая форма*</label>--}}
    {{--<div class="col calc__inpgrp"><input type="text" class="form-control" placeholder="ИП, ООО, АО" required /></div>--}}
    {{--</div>--}}
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">Название организации*</label>--}}
    {{--<div class="col"><input type="text" class="form-control" required /></div>--}}
    {{--</div>--}}
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">Юридический адрес*</label>--}}
    {{--<div class="col">--}}
    {{--<input type="text" class="form-control form-item"  placeholder="Город" required/>--}}
    {{--<input type="text" class="form-control form-item"  placeholder="Улица" required/>--}}
    {{--<div class="input-group">--}}
    {{--<input type="text" class="form-control text-center form-item" placeholder="Дом" />--}}
    {{--<input type="text" class="form-control text-center form-item" placeholder="Корп." />--}}
    {{--<input type="text" class="form-control text-center form-item" placeholder="Стр." />--}}
    {{--<input type="text" class="form-control text-center" placeholder="Кв./оф." />--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">ИНН*</label>--}}
    {{--<div class="col calc__inpgrp"><input type="text" class="form-control" /></div>--}}
    {{--</div>--}}
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">КПП*</label>--}}
    {{--<div class="col calc__inpgrp"><input type="text" class="form-control" /></div>--}}
    {{--</div>--}}
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">Контактная лицо*</label>--}}
    {{--<div class="col calc__inpgrp"><input type="text" class="form-control" /></div>--}}
    {{--</div>--}}
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">Телефон*</label>--}}
    {{--<div class="col calc__inpgrp"><input type="text" class="form-control" /></div>--}}
    {{--</div>--}}
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">Дополнительная информация</label>--}}
    {{--<div class="col"><input type="text" class="form-control" /></div>--}}
    {{--</div>--}}

    <div class="form-item row align-items-center">
        <label class="col-auto calc__label">ФИО</label>
        <div class="col"><input type="text" class="form-control" name="sender_name" required /></div>
    </div>
    <div class="form-item row align-items-center">
        <label class="col-auto calc__label">Телефон*</label>
        <div class="col calc__inpgrp"><input type="text" class="form-control" name="sender_phone" required/></div>
    </div>

    <div class="calc__title">Получатель</div>
    {{--<div class="custom-control custom-radio">--}}
    {{--<input type="radio" class="custom-control-input" id="recipient-legally" name="recipient" required>--}}
    {{--<label class="custom-control-label" for="recipient-legally">Юридическое лицо</label>--}}
    {{--</div>--}}
    {{--<div class="custom-control custom-radio">--}}
    {{--<input type="radio" class="custom-control-input" id="recipient-private" name="recipient" required>--}}
    {{--<label class="custom-control-label" for="recipient-private">Физическое лицо</label>--}}
    {{--</div>--}}
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">Страна*</label>--}}
    {{--<div class="col">--}}
    {{--<div class="relative">--}}
    {{--<i class="dropdown-toggle fa-icon"></i>--}}
    {{--<select class="custom-select">--}}
    {{--<option disabled selected>Выберите из списка</option>--}}
    {{--<option>Россия</option>--}}
    {{--<option>Казахстан</option>--}}
    {{--<option>Грузия</option>--}}
    {{--<option>Индия</option>--}}
    {{--</select>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    <div class="form-item row align-items-center">
        <label class="col-auto calc__label">ФИО</label>
        <div class="col"><input type="text" class="form-control" name="recepient_name" required /></div>
    </div>
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">Паспорт*</label>--}}
    {{--<div class="col calc__inpgrp">--}}
    {{--<div class="input-group">--}}
    {{--<input type="text" class="form-control text-center form-item" placeholder="Серия" required />--}}
    {{--<input type="text" class="form-control text-center form-item" placeholder="Номер" required />--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">Контактное лицо*</label>--}}
    {{--<div class="col calc__inpgrp"><input type="text" class="form-control" required/></div>--}}
    {{--</div>--}}
    <div class="form-item row align-items-center">
        <label class="col-auto calc__label">Телефон*</label>
        <div class="col calc__inpgrp"><input type="text" class="form-control" name="recepient_phone" required/></div>
    </div>
    {{--<div class="form-item row align-items-center">--}}
    {{--<label class="col-auto calc__label">Дополнительная информация</label>--}}
    {{--<div class="col"><input type="text" class="form-control" /></div>--}}
    {{--</div>--}}
    <div class="calc__title">Данные плательщика</div>
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" id="sender" name="payer_type" value="sender" required />
        <label class="custom-control-label" for="sender">Отправитель</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" id="recipient" name="payer_type" value="recipient" required />
        <label class="custom-control-label" for="recipient">Получатель</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" id="3rd-person" name="payer_type" value="3rd-person" required />
        <label class="custom-control-label" for="3rd-person">3-е лицо</label>
    </div>
    <div id="3rd-person-payer"  style="display: none">
        <div class="form-item row align-items-center">
            <label class="col-auto calc__label">ФИО</label>
            <div class="col calc__inpgrp"><input type="text" class="form-control" name="payer_name"/></div>
        </div>
        <div class="form-item row align-items-center">
            <label class="col-auto calc__label">Телефон</label>
            <div class="col calc__inpgrp"><input type="text" class="form-control" name="payer_phone"/></div>
        </div>
    </div>
    <div class="calc__title">Форма оплаты</div>
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" id="available" name="payment" required />
        <label class="custom-control-label" for="available">Наличный расчет</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" id="non-cash" name="payment" required />
        <label class="custom-control-label" for="non-cash">Безналичный расчет</label>
    </div>
    <div class="form-item d-flex">
        <button class="btn margin-item btn-danger">Оформить заказ</button>
        <button class="btn margin-item btn-default">Сохранить черновик</button>
    </div>
    @csrf
</form>