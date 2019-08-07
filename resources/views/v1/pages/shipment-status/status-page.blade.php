@extends('v1.layouts.innerPageLayout')

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
                        <div class="container status-shipment__header">
                            <div class="reports__header row align-items-center">
                                <span class="reports__header-label margin-md-item">Поиск:</span>
                                <div class="margin-md-item d-flex flex-wrap control-group">
                                    <input name="order_id" type="text" maxlength="10" class="form-control search-input" placeholder="Введите номер" value="{{ request()->get('order_id') }}">
                                </div>
                                <button type="submit" class="btn btn-danger">Найти груз</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-xl-6">
                    @if(strlen(request()->get('order_id')) <= 10)
                        @if(request()->get('order_id'))
                            @if(isset($order))
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>№</th>
                                            <th>Дата доставки</th>
                                            <th>Статус заказа</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            @if(isset($order->estimated_delivery_date))
                                                <td>
                                                    {{ \Carbon\Carbon::createFromDate($order->estimated_delivery_date)->format('d.m.Y') }}<br><span class="annotation-text">Плановая дата доставки</span>
                                                </td>
                                            @else
                                                <td>
                                                    уточняется
                                                </td>
                                            @endif
                                            <td>{{ $order->status->name }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                Не найдено заказа с таким номером.
                            @endif
                        @endif
                    @else
                        <span class="text-danger">Превышено допустимое количество символов.</span>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
