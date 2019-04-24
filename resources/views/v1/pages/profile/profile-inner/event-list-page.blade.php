@extends('v1.layouts.innerPageLayout')

@section('content')

    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/klientam') }}" class="">Клиентам</a></span>
            <span class="breadcrumb__item">Лента событий</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Лента событий</h1>
                    </header>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="events-feed">
                                @foreach($events as $event)
                                    <a href="{{ $event->url ? url($event->url) : '#' }}">
                                        <div class="events-feed__item d-flex flex-column">
                                            <div class="events-feed__close" data-event-id="{{ $event->id }}">
                                                <i class="fa fa-close"></i>
                                            </div>
                                            <div class="events-feed__title">{{ $event->name }}</div>
                                            <div class="events-feed__date">{{ $event->created_at->format('h:i d.m.Y') }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
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