@extends('v1.layouts.mainPageLayout')

@section('headerStyles')
    <link rel="stylesheet" href="{{ asset('packages/selectize/selectize.bootstrap4.css') }}@include('v1.partials.versions.cssVersion')"/>
@endsection

@section('footerScripts')
    <script>
        var parameters={
            max_length:10,
            max_width:10,
            max_height:10,
            max_weight:10,
            max_volume:10,
        };
    </script>

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
                @include('v1.pages.calculator.parts.calculator-content')
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