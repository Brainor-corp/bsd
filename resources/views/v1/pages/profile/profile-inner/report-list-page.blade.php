@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/klientam') }}" class="">Клиентам</a></span>
            <span class="breadcrumb__item">Отчеты</span>
{{--            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>--}}
{{--            @foreach($ancestors as $ancestor)--}}
{{--                <span class="breadcrumb__item"><a href="{{ url($ancestor->url) }}" class="">{{ $ancestor->title }}</a></span>--}}
{{--            @endforeach--}}
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
                            <div class="reports__header row align-items-center">
                                <span class="reports__header-label margin-md-item">Поиск:</span>
                                <div class="margin-md-item d-flex flex-wrap control-group">
                                    <select class="custom-select">
                                        <option disabled selected>Выберите из списка</option>
                                        <option>По номеру</option>
                                        <option>По типу</option>
                                    </select>
                                    <input type="text" class="form-control search-input" placeholder="Введите номер">
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="insurance">
                                    <label class="custom-control-label" for="insurance">Только завершенные</label>
                                </div>
                                <button type="button" class="btn btn-dotted ml-auto d-flex align-items-center">
                                    <i class="icons excel-icon margin-item"></i>
                                    <span class="btn-label margin-item">Выгрузить в excel</span>
                                </button>
                            </div>
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
                                        <tbody>
                                        <tr>
                                            <td>456</td>
                                            <td>12.02.2019</td>
                                            <td>
                                                <div>
                                                    <span class="label">Вес:</span>
                                                    <span>112 кг</span>
                                                </div>
                                                <div>
                                                    <span class="label">ДхШхВ:</span>
                                                    <span>3х2х6 м</span>
                                                </div>
                                            </td>
                                            <td>Санкт-Петербург</td>
                                            <td>Екатеринбурн</td>
                                            <td>ИП Вяткино</td>
                                            <td>Смирнов Валерий</td>
                                            <td>9 120 <span>р</span></td>
                                            <td>В очереди на доставку</td>
                                            <td><a href="##" class="table-text-link">Наименование<br />документа</a></td>
                                            <td>
                                                <a href="##" class="table-icon-link"><i class="fa fa-pencil-square-o"></i></a>
                                                <a href="##" class="table-icon-link"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>456</td>
                                            <td>12.02.2019</td>
                                            <td>
                                                <div>
                                                    <span class="label">Вес:</span>
                                                    <span>112 кг</span>
                                                </div>
                                                <div>
                                                    <span class="label">ДхШхВ:</span>
                                                    <span>3х2х6 м</span>
                                                </div>
                                            </td>
                                            <td>Санкт-Петербург</td>
                                            <td>Екатеринбурн</td>
                                            <td>ИП Вяткино</td>
                                            <td>Смирнов Валерий</td>
                                            <td>9 120 <span>р</span></td>
                                            <td>В очереди на доставку</td>
                                            <td><a href="##" class="table-text-link">Наименование<br />документа</a></td>
                                            <td>
                                                <a href="##" class="table-icon-link"><i class="fa fa-pencil-square-o"></i></a>
                                                <a href="##" class="table-icon-link"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>456</td>
                                            <td>12.02.2019</td>
                                            <td>
                                                <div>
                                                    <span class="label">Вес:</span>
                                                    <span>112 кг</span>
                                                </div>
                                                <div>
                                                    <span class="label">ДхШхВ:</span>
                                                    <span>3х2х6 м</span>
                                                </div>
                                            </td>
                                            <td>Санкт-Петербург</td>
                                            <td>Екатеринбурн</td>
                                            <td>ИП Вяткино</td>
                                            <td>Смирнов Валерий</td>
                                            <td>9 120 <span>р</span></td>
                                            <td>В очереди на доставку</td>
                                            <td><a href="##" class="table-text-link">Наименование<br />документа</a></td>
                                            <td>
                                                <a href="##" class="table-icon-link"><i class="fa fa-pencil-square-o"></i></a>
                                                <a href="##" class="table-icon-link"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
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
@endsection