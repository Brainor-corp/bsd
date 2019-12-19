@extends('v1.layouts.innerPageLayout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('v1/css/custom.css') }}@include('v1.partials.versions.cssVersion')">
@endsection

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item">Тарифы</span>
        </div>
    </div>
    <section class="wrapper prices-page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Тарифы</h1>
                    </header>
                    <form action="{{ route('pricesPage') }}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ship_city">Город(а) отправления:</label>
                                    <select id="ship_city" class="form-control point-select" name="ship_city[]" placeholder="Выберите город" data-height="100%" multiple required>
                                        @foreach($shipCities as $shipCity)
                                            <option value="{{ $shipCity->id }}" @if(in_array($shipCity->id, $shipCityIds)) selected @endif>{{ $shipCity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dest_city">Город(а) назначения:</label>
                                    <select id="dest_city" class="form-control point-select" name="dest_city[]" placeholder="Выберите город" data-height="100%" multiple required>
                                        @foreach($destCities as $destCity)
                                            <option value="{{ $destCity->id }}" @if(in_array($destCity->id, $destCityIds)) selected @endif>{{ $destCity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-group">
                                    <button type="submit" name="action" value="show" class="btn margin-item btn-danger sbmt-btn">Показать</button>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-group">
                                    <button type="submit" name="action" value="download" class="btn margin-item btn-danger sbmt-btn">Скачать</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            @include('v1.pages.prices.prices-content')
                        </div>
                    </div>
                    {{--Надпись показываем только для Санкт-Петербурга--}}
                    @if(in_array(78, $shipCityIds) || in_array(78, $destCityIds))
                        <div class="row">
                            <div class="col-12 text-right pt-2">
                                <p>Стоимость включает НДС 20%. Исключение - стоимость экспедирования в городе Санкт-Петербург указана без НДС</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footScripts')
    <script src="{{ asset('v1/js/prices-page.js') }}"></script>
@endsection
