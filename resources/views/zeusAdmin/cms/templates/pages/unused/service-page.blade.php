<?php
/**
 * class: ZeusAdminPageTemplate
 * title: Шаблон устраницы-услуги-одна
 */
?>

@extends('v1.layouts.innerPageLayout')

@section('content')
    @php
        foreach ($page->ancestors as $ancestor)
        {
            $ancestors[] = $ancestor;
        }
        $ancestors[] = $page;
    @endphp

    @include('zeusAdmin.cms.partials.breadcrumbs', ['ancestor' => $ancestors])
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>{{ $page->title }}</h1>
                    </header>
                    <div class="row">
                        <div class="col-8">
                            {!! html_entity_decode($page->content) !!}
                        </div>
                        <div class="col-3 offset-md-1">
                            <div class="sidebar__title">Услуги</div>
                            <nav class="section">
                                <ul class="sidebar__menu">
                                    <li class="sidebar__item"><a href="##">Меж-терминальная перевозка</a></li>
                                    <li class="sidebar__item"><a href="##">Авиаперевозка</a></li>
                                    <li class="sidebar__item"><a href="##">Доставка документов</a></li>
                                    <li class="sidebar__item"><a href="##">Доставка в гипермаркеты</a></li>
                                    <li class="sidebar__item"><a href="##">Контейнерные перевозки</a></li>
                                    <li class="sidebar__item"><a href="##">Прямая машина</a></li>
                                    <li class="sidebar__item"><a href="##">Упаковка</a></li>
                                    <li class="sidebar__item active"><a href="##">Страхование</a></li>
                                    <li class="sidebar__item"><a href="##">Погрузо-разгрузочные работы</a></li>
                                    <li class="sidebar__item"><a href="##">Доставка к точному времени</a></li>
                                </ul>
                            </nav>
                            <section class="section">
                                <div class="order__block d-flex flex-column">
                                    <img src="images/img/delivery-img.png" alt="">
                                    <div class="order__text">Оформить заказ на доставку</div>
                                </div>
                            </section>
                            <section class="section">
                                <div class="sidebar-stock__item d-flex flex-column justify-content-between sidebar-stock-img">
                                    <span class="sidebar-stock__discount d-flex justify-content-center"><span class="amount">25</span><span class="symbol">%</span></span>
                                    <span class="sidebar-stock__title">Скидка 5% на<br />меж терминальную<br />перевозку груза</span>
                                    <span class="sidebar-stock__duration"><i class="fa fa-calendar"></i>с 01.05.2016 по 31.08.2016</span>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection