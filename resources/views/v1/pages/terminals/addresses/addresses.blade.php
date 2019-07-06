@extends('v1.layouts.innerPageLayout')

@section('footerScripts')
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=4c5f0b23-0d16-4e64-a199-19ecfa66c7af" type="text/javascript"></script>
    <script src="{{ asset('v1/js/map.js') }}@include('v1.partials.versions.jsVersion')"></script>
@endsection

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="/" class="">Главная</a></span>
            <span class="breadcrumb__item">Адреса терминалов</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Адреса терминалов</h1>
                    </header>
                </div>
            </div>
        </div>
    </section>
    <div class="map-b">
        <div id="map"></div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="map__contacts">
                        @foreach($terminals as $terminal)
                            <div class="map__contacts-title">{{ $terminal->name }}</div>
                            <div class="map__contacts-list d-flex flex-column terminal-block" data-point="{{ $terminal->geo_point }}">
                                @if(!empty($terminal->address))
                                    <span class="map__contacts-item d-flex">
                                        <i class="fa fa-map-marker"></i>
                                        <span>
                                            {{ $terminal->address ?? '-' }}
                                        </span>
                                    </span>
                                @endif
                                @if(!empty($terminal->phone))
                                    <span class="map__contacts-item d-flex">
                                        <i class="fa fa-phone"></i>
                                        <a href="tel:{{ $terminal->phone }}">{{ $terminal->phone }}</a>
                                    </span>
                                @endif
                            </div>
                            @if(!$loop->last)
                                <div class="separator-hr"></div>
                            @endif
                        @endforeach
                        {{--<div class="map__contacts-title">Терминал в г. Санкт-Петербург ООО «БСД» - «СЕВЕР»</div>--}}
                        {{--<div class="map__contacts-list d-flex flex-column">--}}
                            {{--<span class="map__contacts-item d-flex">--}}
                                {{--<i class="fa fa-map-marker"></i>--}}
                                {{--<span>198095, г. Санкт-Петербург, <br />ул. Верхняя, <br />д. 12</span>--}}
                            {{--</span>--}}
                            {{--<span>--}}
                                {{--<a class="link-with-dotted more-link" href="##">Подробнее</a>--}}
                            {{--</span>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
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
    </div>
@endsection