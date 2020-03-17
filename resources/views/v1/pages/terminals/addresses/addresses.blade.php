@extends('v1.layouts.innerPageLayout')

@section('footerScripts')
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=4c5f0b23-0d16-4e64-a199-19ecfa66c7af" type="text/javascript"></script>
    <script src="{{ asset('v1/js/map.js') }}@include('v1.partials.versions.jsVersion')"></script>
@endsection

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="/" class="">Главная</a></span>
            @if(isset($city))
                <span class="breadcrumb__item"><a href="{{ url('terminals-addresses') }}" class="">Адреса терминалов</a></span>
                <span class="breadcrumb__item">{{ $currentCity->name }}</span>
            @else
                <span class="breadcrumb__item">Адреса терминалов</span>
            @endif
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        @if(isset($city))
                            <h1>{{ $currentCity->name }}</h1>
                        @else
                            <h1>Адреса терминалов</h1>
                        @endif
                    </header>
                </div>
            </div>
        </div>
    </section>
    <div class="map-b position-relative">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="map__contacts">
                        @foreach($terminals as $terminal)
                            <div class="map__contacts-title">{{ $terminal->name }}</div>
                            <div class="map__contacts-list d-flex flex-column terminal-block" data-point="{{ $terminal->geo_point }}">
                                @if(!empty($terminal->address))
                                    <span class="map__contacts-item align-items-baseline d-flex">
                                        <i class="fa fa-map-marker"></i>
                                        <span>
                                            {{ $terminal->address ?? '-' }}
                                        </span>
                                    </span>
                                @endif
                                @if(!empty($terminal->phone))
                                    @php
                                        $phones = preg_split("/(;|,)/", str_replace(' ', '', $terminal->phone));
                                    @endphp
                                    <div class="map__contacts-item align-items-baseline d-flex">
                                        <i class="fa fa-phone"></i>
                                        @foreach($phones as $phone)
                                            <a href="tel:{{ $phone }}">{{ $phone }}</a>
                                            {!! $loop->last ? '' : ",&nbsp;" !!}
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @if(!$loop->last)
                                <div class="separator-hr"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div id="map" class="position-absolute" style="z-index: 0"></div>
    </div>
    <div class="container">
        @foreach($currentCity->requisites->sortBy('sort') as $requisite)
            <div class="row my-4">
                <div class="col-12">
                    <h3>{{ $requisite->name }}</h3>
                    @foreach($requisite->requisiteParts as $requisitePart)
                        <p class="mb-2">
                            <span class="font-weight-bold">{{ $requisitePart->name }}:</span>
                            <span>{{ $requisitePart->value }}</span>
                        </p>
                    @endforeach
                </div>
            </div>
        @endforeach
        @if(isset($currentCity->file))
                <div class="row mb-4">
                    <div class="col-12">
                        <a target="_blank" class="font-weight-bold" href="{{ url($currentCity->file) }}">Скачать схему проезда</a>
                    </div>
                </div>
        @endif
    </div>
@endsection
