@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item">Цены</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Цены</h1>
                    </header>
                    <div class="row">
                        <div class="col-12">
                            <h5>Данные маршрута</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td>Маршрут</td>
                                        <td>Срок доставки</td>
                                        <td>Минимальная цена</td>
                                        <td>Цена бандероли</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $route->name }}</td>
                                        <td>{{ $route->delivery_time }}</td>
                                        <td>{{ $route->min_cost }}</td>
                                        <td>{{ $route->wrapper_tariff }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @if(isset($baseRoute))
                                <h5>Данные базового маршрута</h5>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <td>Маршрут</td>
                                        <td>Срок доставки</td>
                                        <td>Минимальная цена</td>
                                        <td>Цена бандероли</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ $baseRoute->name }}</td>
                                        <td>{{ $baseRoute->delivery_time }}</td>
                                        <td>{{ $baseRoute->min_cost }}</td>
                                        <td>{{ $baseRoute->wrapper_tariff }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif
                            <h5>Тарифы маршрута</h5>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td>Единица оценки</td>
                                    <td>Предел</td>
                                    <td>Цена</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($route->route_tariffs->groupBy('rate_id') as $routeTariffType)
                                    @foreach($routeTariffType as $routeTariff)
                                        <tr>
                                            <td>{{ $routeTariff->rate->name }}</td>
                                            <td>{{ $routeTariff->threshold->value }}</td>
                                            <td>{{ $routeTariff->price }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                            <h5>Стоимость экспедирования в черте города</h5>
                            @forelse($insideForwardings->groupBy('city_id') as $insideForwardingsCities)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Город</th>
                                            @foreach($insideForwardingsCities as $insideForwarding)
                                                <th>
                                                    {{ $insideForwarding->forwardThreshold->name }}
                                                </th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{ $insideForwardingsCities->first()->city->name }}</td>
                                            @foreach($insideForwardingsCities as $insideForwarding)
                                                <td>
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
                            {{--<table class="table table-bordered">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<td>Город</td>--}}
                                    {{--@foreach($insideForwardings->pluck('forwardThreshold')->unique() as $forwardThreshold)--}}
                                        {{--<td>{{ $forwardThreshold->name }}</td>--}}
                                    {{--@endforeach--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($insideForwardings->groupBy('city_id') as $insideForwardingCities)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $insideForwardingCities->first()->city->name }}</td>--}}
                                        {{--@foreach($insideForwardings->pluck('forwardThreshold')->unique() as $forwardThreshold)--}}
                                            {{--@php($forward = $insideForwardingCities->where('forwardThreshold.id', $forwardThreshold->id)->first())--}}
                                            {{--<td>{{ isset($forward) ? $forward->tariff : "" }}</td>--}}
                                        {{--@endforeach--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                            {{--<form action="{{ route('show-prices') }}" method="post" class="reports__header row align-items-center">--}}
                                {{--@csrf--}}
                                {{--<span class="reports__header-label margin-md-item">Поиск:</span>--}}
                                {{--<div id="search-wrapper" class="margin-md-item d-flex flex-wrap control-group">--}}
                                    {{--<select id="search-type-select" class="custom-select">--}}
                                        {{--<option disabled value="" selected>Выберите из списка</option>--}}
                                        {{--<option selected value="id">По номеру</option>--}}
                                        {{--<option value="status">По типу</option>--}}
                                    {{--</select>--}}
                                    {{--<input name="id" id="search-input" type="text" class="form-control search-input" placeholder="Введите номер">--}}
                                {{--</div>--}}
                                {{--<div id="cb-input" class="custom-control custom-checkbox">--}}
                                    {{--<input name="finished" value="true" type="checkbox" class="custom-control-input" id="finished-cb">--}}
                                    {{--<label class="custom-control-label" for="finished-cb">Только завершенные</label>--}}
                                {{--</div>--}}
                                {{--<button type="submit" class="btn btn-dotted ml-auto d-flex align-items-center">--}}
                                    {{--<i class="icons excel-icon margin-item"></i>--}}
                                    {{--<span class="btn-label margin-item">Выгрузить в excel</span>--}}
                                {{--</button>--}}
                            {{--</form>--}}
                            {{--<div class="row">--}}
                                {{--<div class="table-responsive">--}}
                                    {{--<table class="table table-bordered">--}}
                                        {{--<thead>--}}
                                        {{--<tr>--}}
                                            {{--<th>№</th>--}}
                                            {{--<th>Дата</th>--}}
                                            {{--<th>Параметры груза</th>--}}
                                            {{--<th>Город отправителя</th>--}}
                                            {{--<th>Город получателя</th>--}}
                                            {{--<th>Отправитель</th>--}}
                                            {{--<th>Получатель</th>--}}
                                            {{--<th>Стоимость</th>--}}
                                            {{--<th>Статус заказа</th>--}}
                                            {{--<th style="width: 120px">Доступные документы</th>--}}
                                            {{--<th>&nbsp;</th>--}}
                                        {{--</tr>--}}
                                        {{--</thead>--}}
                                        {{--<tbody id="orders-table-body">--}}
                                        {{--@include('v1.partials.profile.orders')--}}
                                        {{--</tbody>--}}
                                    {{--</table>--}}
                                {{--</div>--}}
                            {{--</div>--}}
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