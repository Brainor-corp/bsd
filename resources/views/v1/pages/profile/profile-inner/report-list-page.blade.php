@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/klientam') }}" class="">Клиентам</a></span>
            <span class="breadcrumb__item">Отчеты</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Отчеты</h1>
                    </header>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('download-reports') }}" method="post" class="reports__header row align-items-center">
                                @csrf
                                <span class="reports__header-label margin-md-item">Поиск:</span>
                                <div id="search-wrapper" class="margin-md-item d-flex flex-wrap control-group">
                                    <select id="search-type-select" class="custom-select">
                                        <option disabled value="" selected>Выберите из списка</option>
                                        <option selected value="id">По номеру</option>
                                        <option value="status">По типу</option>
                                    </select>
                                    <input name="id" id="search-input" type="text" class="form-control search-input" placeholder="Введите номер">
                                </div>
                                <div id="cb-input" class="custom-control custom-checkbox">
                                    <input name="finished" value="true" type="checkbox" class="custom-control-input" id="finished-cb">
                                    <label class="custom-control-label" for="finished-cb">Только завершенные</label>
                                </div>
                                <button type="submit" class="btn btn-dotted ml-auto d-flex align-items-center">
                                    <i class="icons excel-icon margin-item"></i>
                                    <span class="btn-label margin-item">Выгрузить в excel</span>
                                </button>
                            </form>
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>№</th>
                                            <th>Дата</th>
                                            <th>Параметры груза</th>
                                            <th>Город отправителя</th>
                                            <th>Город получателя</th>
                                            <th>Отправитель</th>
                                            <th>Получатель</th>
                                            <th>Стоимость</th>
                                            <th>Статус заказа</th>
                                            <th style="width: 120px">Доступные документы</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody id="orders-table-body">
                                            @include('v1.partials.profile.orders')
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="orderItemsModal" tabindex="-1" role="dialog" aria-labelledby="orderItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderItemsModalLabel">Габариты</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="orderDocumentsModal" tabindex="-1" role="dialog" aria-labelledby="orderDocumentsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDocumentsModalLabel">Доступные документы</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li>
                            <a class="btn-link document-link text-info" href="#" data-href="{{ route('download-document-request') }}">Заявка</a>
                        </li>
                        <li>
                            <a class="btn-link document-link text-info" href="#" data-href="{{ route('download-document-invoice') }}">Счет на оплату</a>
                        </li>
                        <li>
                            <a class="btn-link document-link text-info" href="#" data-href="{{ route('download-document-transfer') }}">УПД</a>
                        </li>
                        <li>
                            <a class="btn-link document-link text-info" href="#" data-href="{{ route('download-document-contract') }}">Договор</a>
                        </li>
{{--                        <li>--}}
{{--                            <a class="btn-link document-link text-info" href="#" data-href="{{ route('download-document-receipt') }}">Экспедиторская расписка</a>--}}
{{--                        </li>--}}
                    </ul>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endsection

@section('footScripts')
    <script src="{{ asset('v1/js/profile.js') }}"></script>
@endsection