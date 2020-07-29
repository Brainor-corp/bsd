@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/klientam') }}" class="">Клиентам</a></span>
            <span class="breadcrumb__item">Мои грузы</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <div class="row align-items-center">
                            <div class="col-sm col-12">
                                <h1 class="d-inline-block">Мои грузы</h1>
                            </div>
                            <div class="col-sm col-12 text-sm-right">
                                <a href="{{ route('calculator-show', ['id' => null, 'type' => 'order']) }}">Добавить
                                    заявку</a>
                            </div>
                        </div>
                    </header>
                    <div class="row">
                        <div class="col-12">
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    {{ session()->get('message') }}
                                </div>
                            @endif
                            <div class="row mb-2">
                                <div class="col-12">
                                    <form action="{{ route('orders-list') }}" method="get">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row align-items-end">
                                                    <div class="col-md col-12">
                                                        <div class="form-group mb-md-0 mb-2">
                                                            <label for="number">Номер:</label>
                                                            <input name="number" id="number" type="text"
                                                                   class="form-control search-input"
                                                                   value="{{ app('request')->get('number') }}"
                                                                   placeholder="Введите номер">
                                                        </div>
                                                    </div>
                                                    <div class="col-md col-12">
                                                        <div class="form-group mb-md-0 mb-2">
                                                            <label for="status">Статус:</label>
                                                            <select name="status" id="status" class="custom-select">
                                                                <option value="" selected="">Любой статус</option>
                                                                @foreach($types as $type)
                                                                    <option value="{{ $type->id }}"
                                                                        {{ app('request')->get('status') == $type->id ? 'selected' : '' }}
                                                                    >
                                                                        {{ $type->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md col-12">
                                                        <div class="form-group mb-md-0 mb-2">
                                                            <label for="sender">Отправитель:</label>
                                                            <input name="sender" id="sender" type="text"
                                                                   class="form-control search-input"
                                                                   value="{{ app('request')->get('sender') }}"
                                                                   placeholder="Введите наименование отправителя">
                                                        </div>
                                                    </div>
                                                    <div class="col-md col-12">
                                                        <div class="form-group mb-md-0 mb-2">
                                                            <label for="recipient">Получатель:</label>
                                                            <input name="recipient" id="recipient" type="text"
                                                                   value="{{ app('request')->get('recipient') }}"
                                                                   class="form-control search-input"
                                                                   placeholder="Введите наименование получателя">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto col-12 d-md-block d-none">
                                                        <button type="submit" name="action" value="show"
                                                                class="btn btn-dotted align-items-center mb-2">
                                                            <i class="fa fa-search fa-2x" aria-hidden="true"></i>
                                                            <span class="btn-label margin-item">Найти</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center my-2">
                                            <div class="col-md-6 col-12">
                                                <div id="cb-input" class="custom-control custom-checkbox">
                                                    <input name="finished" value="true" type="checkbox"
                                                           {{ app('request')->get('finished') ? 'checked' : '' }}
                                                           class="custom-control-input" id="finished-cb">

                                                    <label class="custom-control-label" for="finished-cb">Только
                                                        завершенные</label>
                                                </div>
                                            </div>
                                            <div class="col-auto d-md-none d-block">
                                                <button type="submit" name="action" value="show"
                                                        class="btn btn-dotted align-items-center mb-2">
                                                    <i class="fa fa-search fa-2x" aria-hidden="true"></i>
                                                    <span class="btn-label margin-item">Найти</span>
                                                </button>
                                            </div>
                                            <div class="col-md-6 col-auto">
                                                <button type="submit" name="action" value="download"
                                                        class="btn btn-dotted ml-sm-auto d-flex align-items-center">
                                                    <i class="icons excel-icon margin-item"></i>
                                                    <span class="btn-label margin-item">Выгрузить в excel</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered reports-table"
                                               data-documents-modal-url="{{ route('get-download-documents-modal') }}">
                                            <thead>
                                            <tr>
                                                <th>Тип</th>
                                                <th>№ заявки</th>
                                                <th>№ ЭР</th>
                                                <th>Статус груза</th>
                                                <th>Дата</th>
                                                <th>Параметры груза</th>
                                                <th>Город отправителя</th>
                                                <th>Город получателя</th>
                                                <th>Отправитель</th>
                                                <th>Получатель</th>
                                                <th>Оплата услуги</th>
                                                <th>Статус</th>
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
                            <div class="row">
                                <div class="col-12">
                                    {{ $orders->appends($request->input())->links('v1.partials.pagination.pagination') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="orderItemsModal" tabindex="-1" role="dialog" aria-labelledby="orderItemsModalLabel"
         aria-hidden="true">
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
    <div class="modal fade" id="orderDocumentsModal" tabindex="-1" role="dialog"
         aria-labelledby="orderDocumentsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDocumentsModalLabel">Доступные документы</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body documents-container">

                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endsection

@section('footScripts')
    <script src="{{ asset('v1/js/profile.js') }}"></script>
@endsection
