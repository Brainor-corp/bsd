@extends('v1.layouts.innerPageLayout')

@section('headerStyles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
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
    {{--<script src="{{ asset('v1/js/jquery.kladr.js') }}@include('v1.partials.versions.jsVersion')"></script>--}}
    {{--<script src="{{ asset('v1/js/calculator.js') }}@include('v1.partials.versions.jsVersion')"></script>--}}
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('v1/js/calculator-page.js') }}@include('v1.partials.versions.jsVersion')"></script>

@endsection

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="##" class="">Услуги</a></span>
            <span class="breadcrumb__item">Страхование</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Расчет стоимости</h1>
                    </header>
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
                                            <span class="block__itogo_value" id="route-name">{{ $tariff->route->name ?? ''}}</span>
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
                                    <div id="delivery-total-wrapper" style="display: none"
                                    >
                                        <div class="block__itogo_item d-flex">
                                            <div class="d-flex flex-wrap">
                                                <span class="block__itogo_label">Доставка:</span>
                                            </div>
                                        </div>
                                        <div id="delivery-total-list">
                                        </div>
                                    </div>
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
            </div>
        </div>
    </section>
@endsection