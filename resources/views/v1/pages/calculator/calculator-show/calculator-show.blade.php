@extends('v1.layouts.innerPageLayout')

@section('headerStyles')
    <link rel="stylesheet" href="{{ asset('packages/jquery-ui/jquery-ui.css') }}">
@endsection

@section('footerScripts')
    <script>
        var parameters={
            max_length: {{ $oversizeMarkups->where('rate_id', 28)->min('threshold') ?? 3 }},
            max_width: {{ $oversizeMarkups->where('rate_id', 28)->min('threshold') ?? 3 }},
            max_height: {{ $oversizeMarkups->where('rate_id', 28)->min('threshold') ?? 3 }},
            max_weight: {{ $oversizeMarkups->where('rate_id', 26)->min('threshold') ?? 1000 }},
            max_volume: {{ $oversizeMarkups->where('rate_id', 27)->min('threshold') ?? 999 }},
        };
    </script>
    {{--<script src="{{ asset('v1/js/jquery.kladr.js') }}@include('v1.partials.versions.jsVersion')"></script>--}}
    {{--<script src="{{ asset('v1/js/calculator.js') }}@include('v1.partials.versions.jsVersion')"></script>--}}
    <script src="{{ asset('packages/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('v1/js/calculator-page.js') }}@include('v1.partials.versions.jsVersion')"></script>

@endsection

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item">{{ $orderType === 'calculator' ? 'Оформление заявки и расчет стоимости' : 'Заявка на забор груза' }}</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>{{ $orderType === 'calculator' ? 'Расчет стоимости' : 'Заявка на забор груза' }}</h1>
                    </header>
                    <div class="row">
                        <div class="col-lg-6">
                            @include('v1.pages.calculator.parts.calculator-content')
                        </div>
                        <div class="ml-lg-auto col-lg-4">
                            <section class="block__itogo">
                                <div id="calculator-data-preloader" style="
                                    position:absolute;
                                    display: none;
                                    width: 100%;
                                    height: 100%;
                                ">
                                    <img src="{{ asset('/images/loading.svg') }}" style="
                                        top:30%;
                                        left: 42%;
                                        position: absolute;
                                    ">
                                </div>
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

@section('footScripts')
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ env('GOOGLE_CAPTCHA_KEY') }}', {action: 'calculatorForm'}).then(function(token) {
                $('.calculator-form').append('<input type="hidden" name="gToken" value="'+token+'">')
            });
        });
    </script>
@endsection