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
                    <form action="{{ route('shipment-search') }}" method="get">
                        <div class="status-shipment__header">
                            <div class="reports__header row align-items-center">
                                <div class="col-12">
                                    <span class="reports__header-label">Поиск:</span>
                                    <div id="search-wrapper" class="d-flex flex-wrap control-group">
                                        @php($currentType = request()->get('type') === 'cargo_number' ? 'cargo_number' : 'id')
                                        <select name="type" class="custom-select">
                                            <option @if($currentType === 'id') selected @endif value="id">По номеру заявки</option>
                                            <option @if($currentType === 'cargo_number') selected @endif value="cargo_number">По номеру ЭР</option>
                                        </select>
                                        <input name="query"
                                               type="text"
                                               class="form-control search-input mr-3 autocomplete"
                                               placeholder="{{ $currentType === 'id' ? 'Введите номер (напр.: 123)' : 'Введите номер (напр.: СП-00000)' }}"
                                               value="{{ app('request')->get('query') }}"
                                               data-source="{{ route('get-cargo-numbers') }}"
                                               required
                                        >
                                        <button type="submit" class="btn btn-danger">Найти груз</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if(request()->get('query'))
                        @if(isset($orders) && count($orders))
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>№ Заявки</th>
                                        <th>№ ЭР</th>
                                        <th>Дата доставки</th>
                                        <th>Статус заявки</th>
                                        <th>Статус получения груза</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->cargo_number }}</td>
                                            @if(isset($order->estimated_delivery_date))
                                                <td>
                                                    {{ \Carbon\Carbon::createFromDate($order->estimated_delivery_date)->format('d.m.Y') }}<br><span class="annotation-text">Плановая дата доставки</span>
                                                </td>
                                            @else
                                                <td>
                                                    уточняется
                                                </td>
                                            @endif
                                            <td>{{ $order->status->name ?? '' }}</td>
                                            <td>{{ $order->cargo_status->name ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            Заявка не найдена.
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footScripts')
    <script src="{{ asset('packages/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('v1/js/status-page.js') }}"></script>
@endsection
