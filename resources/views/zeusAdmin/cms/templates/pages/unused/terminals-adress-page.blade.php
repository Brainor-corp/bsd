<?php
/**
 * class: ZeusAdminPageTemplate
 * title: Шаблон Адреса терминалов
 */
?>

@section('footerScripts')
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=4c5f0b23-0d16-4e64-a199-19ecfa66c7af" type="text/javascript"></script>
    <script src="{{ asset('v1/js/map.js') }}@include('v1.partials.versions.jsVersion')"></script>
@endsection
@extends('v1.layouts.innerPageLayout')

@section('content')
    @php
        foreach ($page->ancestors as $ancestor)
        {
            $ancestors[] = $ancestor;
        }
        $ancestors[] = $page;
    @endphp

    @include('zeusAdmin.cms.partials.breadcrumbs', ['ancestor' => $ancestors])
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>{{ $page->title }}</h1>
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
                                        <div class="map__contacts-title">Терминальный комплекс в г. Санкт-Петербург</div>
                                        <div class="map__contacts-list d-flex flex-column">
                            <span class="map__contacts-item d-flex">
                                <i class="fa fa-map-marker"></i>
                                <span>198095, г. Санкт-Петербург, <br />Митрофаньевское шоссе, <br />д. 10 A</span>
                            </span>
                                            <span class="map__contacts-item d-flex">
                                <i class="fa fa-phone"></i>
                                <a href="tel:+78126446777">+7 (812) 644-67-77</a>
                            </span>
                                            <span class="map__contacts-item d-flex">
                                <i class="fa fa-envelope-o"></i>
                                <a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a>
                            </span>
                                            <span class="map__contacts-item d-flex">
                                <i class="fa fa-calendar"></i>
                                <span>пн - пт с 9:00 до 18:00, <br />сб с 10:00 до 14:00, <br />вс - выходной</span>
                            </span>
                                        </div>
                                        <div class="separator-hr"></div>
                                        <div class="map__contacts-title">Терминал в г. Санкт-Петербург ООО «БСД» - «СЕВЕР»</div>
                                        <div class="map__contacts-list d-flex flex-column">
                            <span class="map__contacts-item d-flex">
                                <i class="fa fa-map-marker"></i>
                                <span>198095, г. Санкт-Петербург, <br />ул. Верхняя, <br />д. 12</span>
                            </span>
                                            <span>
                                <a class="link-with-dotted more-link" href="##">Подробнее</a>
                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



@endsection