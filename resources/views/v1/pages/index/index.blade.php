@extends('v1.layouts.mainPageLayout')

@section('headerStyles')
    <link rel="stylesheet" href="{{ asset('packages/selectize/selectize.bootstrap4.css') }}@include('v1.partials.versions.cssVersion')"/>
@endsection

@section('footerScripts')
    <script src="{{ asset('packages/selectize/selectize.min.js') }}@include('v1.partials.versions.jsVersion')"></script>
    <script src="{{ asset('v1/js/jquery.kladr.js') }}@include('v1.partials.versions.jsVersion')"></script>
    <script src="{{ asset('v1/js/calculator.js') }}@include('v1.partials.versions.jsVersion')"></script>
    <script src="{{ asset('v1/js/calculator-page.js') }}@include('v1.partials.versions.jsVersion')"></script>
    <script src="{{ asset('v1/js/short-calculator.js') }}@include('v1.partials.versions.jsVersion')"></script>
@endsection

@section('content')
<section class="service">
    <div class="container">
        <div class="title-inline d-flex align-items-center">
            <div class="margin-item"><h3>Услуги</h3></div>
            <a href="{{ url('/uslugi') }}" class="link-with-dotted margin-item">Все услуги</a>
        </div>
        <div class="row row-item justify-content-md-center">
            <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                <div class="service__block d-flex relative mezhter">
                    <div class="service__block_body">
                        <div class="service__block_title">Меж-терминальная перевозка</div>
                        <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                <div class="service__block d-flex relative aviap">

                    <div class="service__block_body">
                        <div class="service__block_title"><a href="#">Авиаперевозка</a></div>
                        <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-item justify-content-md-center">
            <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                <div class="service__block d-flex relative perevozkadoc">
                    <div class="service__block_body">
                        <div class="service__block_title">Доставка документов</div>
                        <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                <div class="service__block d-flex relative dostavkavgip">
                    <div class="service__block_body">
                        <div class="service__block_title">Доставка в гипермаркеты</div>
                        <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="about-company bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-7">
                <div class="title-inline d-flex align-items-center">
                    <div class="whiteTxtColor margin-item"><h3>О компании</h3></div>
                    <a class="margin-item darkTxtColor link-with-dotted" href="{{ url('/o-kompanii') }}">Подробнее</a>
                </div>
                <p class="darkTxtColor font-medium mw-95 mb-10">ООО «Балтийская Служба Доставки» заслужила репутацию честного и выгодного партнера на Российском рынке грузоперевозок, что важно для любой компании. Одна из основных задач, которые мы перед собой ставим - минимизация времени, необходимого для оформления груза перед отправкой.</p>
                <div class="clearfix mb-10">
                    <p class="playfairdisplay d-block float-left font-medium p_ourwork">Наша работа - решение проблем по доставке Вашего груза!</p>
                    <p class="d-block float-left darkTxtColor pr-50 p_ourfils">Наши филиалы находятся в городах: Санкт-Петербург, Москва, Нижний Новгород, Ростов-на-Дону, Таганрог, Астрахань, Волгоград, Краснодар, Пятигорск</p>
                </div>
                <p class="darkTxtColor font-medium mw-95">Компания ООО «БСД» также осуществляет автоматическую доставку груза до дверей клиента по Ростовской области, Краснодарскому, Ставропольскому краям и по Северному Кавказу.</p>
            </div>
            <div class="col-sm-12 col-md-5 d-flex">
                <ul class="list-unsetyled stat_list pt-40 d-flex flex-column">
                    <li class="stat__item d-flex align-items-center">
                        <span class="stat__item-amount margin-item">9</span>
                        <span class="margin-item">лет занимаеимся<br />грузоперевозками</span>
                    </li>
                    <li class="stat__item d-flex align-items-center">
                        <span class="stat__item-amount margin-item">114</span>
                        <span class="margin-item">сотрудников<br />работает в штате</span>
                    </li>
                    <li class="stat__item d-flex align-items-center">
                        <span class="stat__item-amount margin-item">316</span>
                        <span class="margin-item">доставляем грузы<br />по 316 городам</span>
                    </li>
                    <li class="stat__item d-flex align-items-center">
                        <span class="stat__item-amount margin-item">2 678</span>
                        <span class="margin-item">терминалов по<br />всей России</span>
                    </li>
                    <li class="stat__item d-flex align-items-center">
                        <span class="stat__item-amount margin-item">5 682</span>
                        <span class="margin-item">раза выполнили<br />доставку</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="calculation">
    <div class="container">
        <h3>Расчет стоимости</h3>
        <div class="row">
            <div class="col-md-6">
                <form class="calculator-form" action="/calculator-show" method="post">
                    <div class="calc__title">Груз</div>
                    @if(isset($packages))
                        @foreach($packages as $key=>$package)
                            <div id="package-{{ $key }}" class="package-item" data-package-id="{{ $key }}">
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Наименование груза*</label>
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Введите наименование груза" name="packages[{{ $key }}][name]" @if(isset($package['name'])) value="{{ $package['name'] }}" @endif>
                                    </div>
                                </div>
                                {{--<div class="form-item row align-items-center">--}}
                                {{--<label class="col-auto calc__label">Тип груза*</label>--}}
                                {{--<div class="col">--}}
                                {{--<div class="relative">--}}
                                {{--<i class="dropdown-toggle fa-icon"></i>--}}
                                {{--<select class="custom-select package-params" name="packages[{{ $key }}][type]">--}}
                                {{--<option disabled selected>Выберите из списка</option>--}}
                                {{--<option>1</option>--}}
                                {{--<option>2</option>--}}
                                {{--<option>3</option>--}}
                                {{--<option>4</option>--}}
                                {{--<option>5</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                <div class="row">
                                    <div class="col-8">
                                        <div class="form-item row align-items-center">
                                            <label class="col-auto calc__label">Габариты (м)*</label>
                                            <div class="col calc__inpgrp relative row__inf">
                                                <div class="input-group">
                                                    <input type="text" id="packages_{{ $key }}_length" class="form-control text-center package-params package-dimensions" name="packages[{{ $key }}][length]" data-package-id="{{ $key }}" data-dimension-type="length" placeholder="Д" @if(isset($package['length'])) value="{{ $package['length'] }}" @endif/>
                                                    <input type="text" id="packages_{{ $key }}_width" class="form-control text-center package-params package-dimensions" name="packages[{{ $key }}][width]" data-package-id="{{ $key }}"  data-dimension-type="width" placeholder="Ш" @if(isset($package['width'])) value="{{ $package['width'] }}" @endif/>
                                                    <input type="text" id="packages_{{ $key }}_height" class="form-control text-center package-params package-dimensions" name="packages[{{ $key }}][height]" data-package-id="{{ $key }}"  data-dimension-type="height" placeholder="В" @if(isset($package['height'])) value="{{ $package['height'] }}" @endif/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-item row align-items-center">
                                            <label class="col-auto calc__label">Вес груза (кг)*</label>
                                            <div class="col calc__inpgrp">
                                                <input type="text" class="form-control package-params package-weight" name="packages[{{ $key }}][weight]" data-package-id="{{ $key }}"  data-dimension-type="weight" @if(isset($package['weight'])) value="{{ $package['weight'] }}" @endif/></div>
                                        </div>
                                        <div class="form-item row align-items-center">
                                            <label class="col-auto calc__label">Объем (м<sup>3</sup>)*</label>
                                            <div class="col calc__inpgrp">
                                                <input type="text" id="packages_{{ $key }}_volume" class="form-control package-params package-volume" name="packages[{{ $key }}][volume]" data-package-id="{{ $key }}"  data-dimension-type="volume" @if(isset($package['volume'])) value="{{ $package['volume'] }}" @endif/></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <p class="calc__info">Габариты груза влияют на расчет стоимости, без их указания стоимость может быть неточной</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div id="package-1" class="package" data-package-id="1">
                            <div class="form-item row align-items-center">
                                <label class="col-auto calc__label">Наименование груза*</label>
                                <div class="col"><input type="text" class="form-control" placeholder="email@example.com"></div>
                            </div>
                            <div class="form-item row align-items-center">
                                <label class="col-auto calc__label">Тип груза*</label>
                                <div class="col">
                                    <div class="relative">
                                        <i class="dropdown-toggle fa-icon"></i>
                                        <select class="custom-select package-params">
                                            <option disabled selected>Выберите из списка</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-item row align-items-center">
                                        <label class="col-auto calc__label">Габариты (м)*</label>
                                        <div class="col calc__inpgrp relative row__inf">
                                            <div class="input-group">
                                                <input type="text" id="packages_1_length" class="form-control text-center package-params package-dimensions" name="packages[1][length]" data-package-id="1" data-dimension-type="length" placeholder="Д" />
                                                <input type="text" id="packages_1_width" class="form-control text-center package-params package-dimensions" name="packages[1][width]" data-package-id="1" data-dimension-type="width" placeholder="Ш" />
                                                <input type="text" id="packages_1_height" class="form-control text-center package-params package-dimensions" name="packages[1][height]" data-package-id="1" data-dimension-type="height" placeholder="В" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-item row align-items-center">
                                        <label class="col-auto calc__label">Вес груза (кг)*</label>
                                        <div class="col calc__inpgrp"><input type="text" id="packages_1_weight" class="form-control package-params package-weight" name="packages[1][weight]" data-package-id="1" data-dimension-type="weight"/></div>
                                    </div>
                                    <div class="form-item row align-items-center">
                                        <label class="col-auto calc__label">Объем (м<sup>3</sup>)*</label>
                                        <div class="col calc__inpgrp"><input type="text" id="packages_1_volume" class="form-control package-params package-volume" name="packages[1][volume]" data-package-id="1" data-dimension-type="volume"/></div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <p class="calc__info">Габариты груза влияют на расчет стоимости, без их указания стоимость может быть неточной</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <a href="#" id="add-package-btn" class="add_anotherplace">
                                    <span class="badge calc_badge">
                                        <i class="fa fa-plus"></i>
                                    </span>
                        Добавить еще одно место
                    </a>
                    <div class="form-item row">
                        <label class="col-auto calc__label big">Откуда</label>
                        <div class="col">
                            <div class="form-item">
                                {{--<input type="text" class="form-control" placeholder="email@example.com">--}}
                                <select id="ship_city" class="form-control" name="ship_city" placeholder="Москва">
                                    <option value=""></option>
                                    @if($shipCities->count() > 0)
                                        @foreach($shipCities as $shipCity)
                                            <option value="{{ $shipCity->id }}" @if(53 == $shipCity->id) selected @endif>{{ $shipCity->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="need-to-take" name="need-to-take">
                                {{--<label class="custom-control-label" for="bring-your-own">Самостоятельно привезти груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                                <label class="custom-control-label" for="need-to-take">Нужно забрать груз из:</label>
                            </div>

                            <div class="form-item ininner">
                                <div class="relative">
                                    <i class="dropdown-toggle fa-icon"></i>
                                    <input class="form-control suggest_address" id="need-to-take-adress" name="need-to-take-adress" placeholder="Название населенного пункта или адрес">
                                </div>
                                {{--<div class="form-group-unlink"><a href="#" class="link-with-dotted">Выбрать отделение на карте</a></div>--}}
                            </div>
                            {{--<div class="custom-control custom-radio">--}}
                            {{--<input type="radio" class="custom-control-input" id="pick-up-cargo" name="from" required>--}}
                            {{--<label class="custom-control-label" for="pick-up-cargo">Забрать груз от адреса отправителя (1 050 <span class="rouble">p</span>)</label>--}}
                            {{--</div>--}}
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="ship-from-point" name="ship-from-point">
                                {{--<label class="custom-control-label" for="you-can-pick">Самостоятельно забрать груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                                <label class="custom-control-label" for="ship-from-point">Доставку груза необходимо произвести в гипермаркете, распределительном центре или в точное время (временно́е "окно" менее 1 часа).</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row">
                        <label class="col-auto calc__label big">Куда</label>
                        <div class="col">
                            <div class="form-item">
                                {{--<input type="text" class="form-control" placeholder="email@example.com">--}}
                                <select id="dest_city" class="form-control" name="dest_city" placeholder="Москва">
                                    <option value=""></option>
                                    @if(isset($destinationCities))
                                        @foreach($destinationCities as $destinationCity)
                                            <option value="{{ $destinationCity->id }}" @if(78 == $destinationCity->id) selected @endif>{{ $destinationCity->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="need-to-bring" name="need-to-bring" required>
                                {{--<label class="custom-control-label" for="you-can-pick">Самостоятельно забрать груз  на терминал (100 <span class="rouble">p</span>)</label>--}}
                                <label class="custom-control-label" for="need-to-bring">Нужно доставить груз в:</label>
                            </div>
                            <div class="form-item ininner">
                                <div class="relative">
                                    <i class="dropdown-toggle fa-icon"></i>
                                    <input class="form-control suggest_address"  id="need-to-bring-address" name="need-to-bring-address" placeholder="Название населенного пункта или адрес">
                                </div>
                                {{--<div class="form-group-unlink"><a href="#" class="link-with-dotted">Выбрать отделение на карте</a></div>--}}
                            </div>
                            {{--<div class="custom-control custom-radio">--}}
                            {{--<input type="radio" class="custom-control-input" id="deliver-cargo" name="where" required>--}}
                            {{--<label class="custom-control-label" for="deliver-cargo">Доставить груз до адреса получателя (1 050 <span class="rouble">p</span>)</label>--}}
                            {{--</div>--}}
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="bring-to-point" name="bring-to-point" required>
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
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="sender-legally" name="sender" required />
                        <label class="custom-control-label" for="sender-legally">Юридическое лицо</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="sender-private" name="sender" required />
                        <label class="custom-control-label" for="sender-private">Физическое лицо</label>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Правовая форма*</label>
                        <div class="col calc__inpgrp"><input type="text" class="form-control" placeholder="ИП, ООО, АО" required /></div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Название организации*</label>
                        <div class="col"><input type="text" class="form-control" required /></div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Юридический адрес*</label>
                        <div class="col">
                            <input type="text" class="form-control form-item"  placeholder="Город" required/>
                            <input type="text" class="form-control form-item"  placeholder="Улица" required/>
                            <div class="input-group">
                                <input type="text" class="form-control text-center form-item" placeholder="Дом" />
                                <input type="text" class="form-control text-center form-item" placeholder="Корп." />
                                <input type="text" class="form-control text-center form-item" placeholder="Стр." />
                                <input type="text" class="form-control text-center" placeholder="Кв./оф." />
                            </div>
                        </div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">ИНН*</label>
                        <div class="col calc__inpgrp"><input type="text" class="form-control" /></div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">КПП*</label>
                        <div class="col calc__inpgrp"><input type="text" class="form-control" /></div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Контактная лицо*</label>
                        <div class="col calc__inpgrp"><input type="text" class="form-control" /></div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Телефон*</label>
                        <div class="col calc__inpgrp"><input type="text" class="form-control" /></div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Дополнительная информация</label>
                        <div class="col"><input type="text" class="form-control" /></div>
                    </div>
                    <div class="calc__title">Получатель</div>
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="recipient-legally" name="recipient" required>
                        <label class="custom-control-label" for="recipient-legally">Юридическое лицо</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="recipient-private" name="recipient" required>
                        <label class="custom-control-label" for="recipient-private">Физическое лицо</label>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Страна*</label>
                        <div class="col">
                            <div class="relative">
                                <i class="dropdown-toggle fa-icon"></i>
                                <select class="custom-select">
                                    <option disabled selected>Выберите из списка</option>
                                    <option>Россия</option>
                                    <option>Казахстан</option>
                                    <option>Грузия</option>
                                    <option>Индия</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">ФИО</label>
                        <div class="col"><input type="text" class="form-control" required /></div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Паспорт*</label>
                        <div class="col calc__inpgrp">
                            <div class="input-group">
                                <input type="text" class="form-control text-center form-item" placeholder="Серия" required />
                                <input type="text" class="form-control text-center form-item" placeholder="Номер" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Контактное лицо*</label>
                        <div class="col calc__inpgrp"><input type="text" class="form-control" required/></div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Телефон*</label>
                        <div class="col calc__inpgrp"><input type="text" class="form-control" required/></div>
                    </div>
                    <div class="form-item row align-items-center">
                        <label class="col-auto calc__label">Дополнительная информация</label>
                        <div class="col"><input type="text" class="form-control" /></div>
                    </div>
                    <div class="calc__title">Данные плательщика</div>
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="sender" name="payer" required />
                        <label class="custom-control-label" for="sender">Отправитель</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="recipient" name="payer" required />
                        <label class="custom-control-label" for="recipient">Получатель</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="3rd-person" name="payer" required />
                        <label class="custom-control-label" for="3rd-person">3-е лицо</label>
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
            </div>
            <div class="col-md-4 offset-md-2">
                <section class="block__itogo">
                    <div class="block__itogo-inner">
                        <header class="block__itogo_title">Перевозка груза включает</header>
                        {{--<div class="block__itogo_item d-flex">--}}
                        {{--<div class="d-flex flex-wrap">--}}
                        {{--<span class="block__itogo_label">Забор груза:</span>--}}
                        {{--<span class="block__itogo_value">Терминал</span>--}}
                        {{--</div>--}}
                        {{--<span class="block__itogo_price d-flex flex-nowrap">--}}
                        {{--<span class="block__itogo_amount">155</span>--}}
                        {{--<span class="rouble">p</span>--}}
                        {{--</span>--}}
                        {{--</div>--}}
                        <div class="block__itogo_item d-flex">
                            <div class="d-flex flex-wrap">
                                <span class="block__itogo_label">Межтерминальная перевозка:</span>
                                <span class="block__itogo_value">{{ $tariff->route->name ?? ''}}</span>
                            </div>
                            <span class="block__itogo_price d-flex flex-nowrap">
                                            <span class="block__itogo_amount" id="base-price" data-base-price="{{ $tariff->base_price ?? 0}}">{{ $tariff->base_price ?? 0}}</span>
                                            <span class="rouble">p</span>
                                        </span>
                        </div>
                        {{--<div class="block__itogo_item d-flex">--}}
                        {{--<div class="d-flex flex-wrap">--}}
                        {{--<span class="block__itogo_label">Доставка груза:</span>--}}
                        {{--<span class="block__itogo_value">Терминал</span>--}}
                        {{--</div>--}}
                        {{--<span class="block__itogo_price d-flex flex-nowrap">--}}
                        {{--<span class="block__itogo_amount">155</span>--}}
                        {{--<span class="rouble">p</span>--}}
                        {{--</span>--}}
                        {{--</div>--}}
                        <div id="custom-services-total-wrapper"
                             @if(
                             !isset($tariff->total_data->services) &&
                             !isset($tariff->total_data->insurance) &&
                             !isset($tariff->total_data->discount)
                             )
                             style="display: none"
                                @endif
                        >
                            <div class="block__itogo_item d-flex">
                                <div class="d-flex flex-wrap">
                                    <span class="block__itogo_label">Дополнительные услуги:</span>
                                </div>
                            </div>
                            <div id="custom-services-total-list">
                                @if(isset($tariff->total_data->services))
                                    @foreach($tariff->total_data->services as $service)
                                        <div class="custom-service-total-item">
                                            <div class="block__itogo_item d-flex">
                                                <div class="d-flex flex-wrap" id="services-total-names">
                                                    <span class="block__itogo_value">{{ $service->name }}</span>
                                                </div>
                                                <span class="block__itogo_price d-flex flex-nowrap"  id="services-total-prices">
                                                                <span class="block__itogo_amount">{{ $service->total }}</span>
                                                                <span class="rouble">p</span>
                                                            </span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                @if(isset($tariff->total_data->insurance))
                                    <div class="custom-service-total-item">
                                        <div class="block__itogo_item d-flex">
                                            <div class="d-flex flex-wrap" id="services-total-names">
                                                <span class="block__itogo_value">Страхование</span>
                                            </div>
                                            <span class="block__itogo_price d-flex flex-nowrap"  id="services-total-prices">
                                                        <span class="block__itogo_amount">{{ $tariff->total_data->insurance }}</span>
                                                        <span class="rouble">p</span>
                                                    </span>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($tariff->total_data->discount))
                                    <div class="custom-service-total-item">
                                        <div class="block__itogo_item d-flex">
                                            <div class="d-flex flex-wrap" id="services-total-names">
                                                <span class="block__itogo_value">Скидка</span>
                                            </div>
                                            <span class="block__itogo_price d-flex flex-nowrap"  id="services-total-prices">
                                                    <span class="block__itogo_amount">{{ $tariff->total_data->discount }}</span>
                                                    <span class="rouble">p</span>
                                                </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="separator-hr"></div>
                        <footer class="block__itogo_footer d-flex">
                            <span>Стоимость перевозки*</span>
                            <span class="block__itogo_price d-flex flex-nowrap">
                                            <span class="block__itogo_amount"><span id="total-price"> {{ $tariff->total_data->total ?? 0}}</span></span>
                                            <span class="rouble">p</span>
                                            <span id="total-volume" data-total-volume="{{ $tariff->total_volume ?? 0.01}}" style="display: none"></span>
                                        </span>
                        </footer>
                    </div>
                    <div class="annotation-text">* - Предварительный расчет. Точная стоимость доставки будет определена после обмера груза специалистами компании БСД на складе.</div>
                </section>
            </div>
        </div>
    </div>
</section>
@endsection