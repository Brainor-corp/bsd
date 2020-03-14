@extends('v1.layouts.innerPageLayout')

@section('headerStyles')
    <link rel="stylesheet" href="{{ asset('packages/jquery-ui/jquery-ui.css') }}">
@endsection

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/klientam') }}" class="">Клиентам</a></span>
            <span class="breadcrumb__item">Статус груза</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Статус груза</h1>
                    </header>
                    <div class="status-shipment__header">
                        <div class="reports__header row align-items-center">
                            <div class="col-12">
                                Результат поиска по <strong>{{ $type == 'id' ? 'номеру заявки' : 'номеру ЭР' }}</strong>: <strong>{{ $number }}</strong>.
                                <a href="{{ route('shipment-search') }}">Искать другой груз</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <input type="hidden" name="number" value="{{ $number ?? '' }}">
                    <input type="hidden" name="type" value="{{ $type ?? '' }}">
                    <div class="result-wrapper">
                        <img class="loading-svg mr-2" src="{{ asset('images/loading.svg') }}" alt="">
                        Поиск..
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footScripts')
    <script src="{{ asset('packages/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('v1/js/shipment-status.js') }}"></script>
@endsection
