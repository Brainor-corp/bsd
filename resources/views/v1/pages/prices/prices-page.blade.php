@extends('v1.layouts.innerPageLayout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('v1/css/custom.css') }}@include('v1.partials.versions.cssVersion')">
@endsection

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item">Цены</span>
        </div>
    </div>
    <section class="wrapper prices-page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Цены</h1>
                    </header>
                    <form action="{{ route('pricesPage') }}" method="get">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ship_city">Город отправления:</label>
                                    <select id="ship_city" class="form-control point-select" name="ship_city" placeholder="Выберите город" data-height="100%" required>
                                        @foreach($shipCities as $shipCity)
                                            <option value="{{ $shipCity->id }}" @if($shipCityId == $shipCity->id) selected @endif>{{ $shipCity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dest_city">Город назначения:</label>
                                    <select id="dest_city" class="form-control point-select" name="dest_city" placeholder="Выберите город" data-height="100%" required>
                                        @foreach($destCities as $destCity)
                                            <option value="{{ $destCity->id }}" @if($destCityId == $destCity->id) selected @endif>{{ $destCity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-group">
                                    <button type="submit" class="btn margin-item btn-danger sbmt-btn">Показать</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            @if(isset($route))
                                <h5>Тарифы на грузоперевозки маршрута <strong>{{ $route->name }}</strong></h5>

                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="align-middle">
                                                <span>Бандероль до 0,01 м3 до 2 кг</span>
                                            </th>
                                            <th rowspan="2" class="align-middle">
                                                <span>мин. стоимость руб.</span>
                                            </th>
                                            @if($route->route_tariffs->where('rate.slug', 'ves')->count() > 0)
                                                <th colspan="{{ $route->route_tariffs->where('rate.slug', 'ves')->count() }}">стоимость за 1кг руб.</th>
                                            @endif
                                            @if($route->route_tariffs->where('rate.slug', 'obem')->count() > 0)
                                                <th colspan="{{ $route->route_tariffs->where('rate.slug', 'obem')->count() }}">стоимость за 1м3 руб.</th>
                                            @endif
                                            <th rowspan="2" class="align-middle">
                                                <span>мин. срок доставки*</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            @foreach($route->route_tariffs->where('rate.slug', 'ves') as $routeTariff)
                                                <th class="align-middle">До {{ $routeTariff->threshold->value }}кг</th>
                                            @endforeach
                                            @foreach($route->route_tariffs->where('rate.slug', 'obem') as $routeTariff)
                                                <th class="align-middle">
                                                    До {{ $routeTariff->threshold->value }}м<sup>3</sup>
                                                </th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="align-middle">
                                                {{ $route->wrapper_tariff }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $route->min_cost }}
                                            </td>
                                            @foreach($route->route_tariffs->where('rate.slug', 'ves') as $routeTariff)
                                                <td class="align-middle">{{ $routeTariff->price }}</td>
                                            @endforeach
                                            @foreach($route->route_tariffs->where('rate.slug', 'obem') as $routeTariff)
                                                <td class="align-middle">{{ $routeTariff->price }}</td>
                                            @endforeach
                                            <td class="align-middle">
                                                {{ $route->delivery_time }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <h5>Стоимость экспедирования в черте города</h5>
                                @forelse($insideForwardings->groupBy('city_id') as $insideForwardingsCities)
                                    <div class="table-responsive mb-3">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                            <tr>
                                                <th class="align-middle">Город</th>
                                                @foreach($insideForwardingsCities as $insideForwarding)
                                                    <th class="align-middle">
                                                        {{ $insideForwarding->forwardThreshold->name }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="align-middle">{{ $insideForwardingsCities->first()->city->name }}</td>
                                                @foreach($insideForwardingsCities as $insideForwarding)
                                                    <td class="align-middle">
                                                        {{ $insideForwarding->tariff }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    @foreach($insideForwardingsCities as $insideForwarding)

                                    @endforeach
                                @empty
                                    <span>Информация отсутствует</span>
                                @endforelse
                            @else
                                <span>Выберите город отправления и город назначения</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footScripts')
    <script src="{{ asset('v1/js/prices-page.js') }}"></script>
@endsection