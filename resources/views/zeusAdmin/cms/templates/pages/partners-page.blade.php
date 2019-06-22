<?php
/**
 * class: ZeusAdminPageTemplate
 * title: Шаблон Партнеры
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

    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            @foreach($ancestors as $ancestor)
                <span class="breadcrumb__item"><a href="{{ url($ancestor->url) }}" class="">{{ $ancestor->title }}</a></span>
            @endforeach
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>{{ $page->title }}</h1>
                    </header>
                    <div class="container">
                        <div class="row partners__list">
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                            <div class="partners__item d-flex justify-content-center align-items-center">
                                <img src="images/img/partner.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <section class="section">
                                <header class="wrapper__header">
                                    <h2>Предложение вступить в наши ряды</h2>
                                </header>
                                <p>ООО «Балтийская Служба Доставки» приглашает к сотрудничеству региональные транспортные компании, специализирующиеся на доставке сборных грузов по территории Российской Федерации.</p>
                                <ul class="styles-list">
                                    <li>Опыт работы в логистике, специализация в перевозке сборных грузов.</li>
                                    <li>Наличие склада.</li>
                                    <li>Оптимальное ценовое предложение и возможность ценового компромисса.</li>
                                    <li>Готовность к изменениям, стандартизации бизнес-процессов.</li>
                                    <li>Ориентация на долгосрочные партнерские отношения.</li>
                                    <li>Соблюдение, согласованных сроков и регулярности перевозок.</li>
                                    <li>Знание рынка транспортных услуг в своем регионе.</li>
                                </ul>
                            </section>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-block">
                                <div class="form-item d-flex align-items-center justify-content-between calc__block_inpg">
                                    <label class="calc__label_max">ФИО</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>
                                <div class="form-item d-flex align-items-center justify-content-between calc__block_inpg">
                                    <label class="calc__label_max">Название компании</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>
                                <div class="form-item d-flex align-items-center justify-content-between calc__block_inpg">
                                    <label class="calc__label_max">Телефон</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>
                                <button class="btn btn-danger">Отправить заявку</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </section>


@endsection