@extends('v1.layouts.innerPageLayout')

@section('headStyles')
@endsection

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ route('profile-data-show') }}" class="">Кабинет</a></span>
            <span class="breadcrumb__item">Мои котрагенты</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Мои котрагенты</h1>
                    </header>
                    <div class="row">
                        <div class="col-8">
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered text-center">
                                    <thead>
                                    <tr>
                                        <th class="align-middle"><span>Активирован</span></th>
                                        <th class="align-middle"><span>Тип</span></th>
                                        <th class="align-middle"><span>Название</span></th>
                                        <th class="align-middle"><span>Юр.Адрес</span></th>
                                        <th class="align-middle"><span>ИНН</span></th>
                                        <th class="align-middle"><span>КПП</span></th>
                                        <th class="align-middle"><span>Телефон</span></th>
                                        <th class="align-middle"><span>Имя</span></th>
                                        <th class="align-middle"><span>Паспорт</span></th>
                                        <th class="align-middle"><span>Дополнительно</span></th>
                                        <th class="align-middle"><span>Контактное лицо</span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($counterparties as $counterparty)
                                            <tr>
                                                <td class="align-middle">{{ $counterparty->active ? 'Да' : 'Нет'}}</td>
                                                <td class="align-middle">{{ $counterparty->legal_form  ?? ''}}</td>
                                                <td class="align-middle">{{ $counterparty->company_name  ?? ''}}</td>
                                                <td class="align-middle">
                                                    {{ $counterparty->legal_address_city  ?? ''}},
                                                    {{ $counterparty->legal_address_street  ?? ''}},
                                                    {{ $counterparty->legal_address_house  ?? ''}},
                                                    {{ $counterparty->legal_address_block  ?? ''}},
                                                    {{ $counterparty->legal_address_building  ?? ''}},
                                                    {{ $counterparty->legal_address_apartment  ?? ''}}
                                                </td>
                                                <td class="align-middle">{{ $counterparty->inn  ?? ''}}</td>
                                                <td class="align-middle">{{ $counterparty->kpp  ?? ''}}</td>
                                                <td class="align-middle">{{ $counterparty->phone  ?? ''}}</td>
                                                <td class="align-middle">{{ $counterparty->name  ?? ''}}</td>
                                                <td class="align-middle">{{ $counterparty->passport_series  ?? ''}} {{ $counterparty->passport_number  ?? ''}}</td>
                                                <td class="align-middle">{{ $counterparty->addition_info  ?? ''}}</td>
                                                <td class="align-middle">{{ $counterparty->contact_person  ?? ''}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-3 offset-md-1">
                            <div class="sidebar__block">
                                <div class="sidebar__image">
                                    <img src="{{ asset('/images/img/news-img.png') }}" alt="С новым годом">
                                </div>
                                <div class="sidebar__body">
                                    <h5>С наступающим новым годом!</h5>
                                    <span>Дорогие друзья! Компания «БСД» поздравляет Вас с Новым годом и Рождеством! Спасибо за то, что были с нами эти 365 дней!</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footScripts')
@endsection