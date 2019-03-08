@extends('v1.layouts.innerPageLayout')


@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/o-kompanii') }}" class="">О компании</a></span>
            <span class="breadcrumb__item">Акции</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container stock_list">
            <header class="wrapper__header">
                <h1>Акции</h1>
            </header>
            <div class="row row-item">
                <div class="col-12 col-sm-6">
                    <div class="stock__item d-flex flex-column justify-content-center">
                        <span class="stock__discount d-flex justify-content-center"><span class="amount">5</span><span class="symbol">%</span></span>
                        <span class="stock__title">Скидка 5% на меж терминальную перевозку груза</span>
                        <span class="stock__duration"><i class="fa fa-calendar"></i>с 01.05.2016 по 31.08.2016</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="stock__item d-flex flex-column justify-content-center">
                        <span class="stock__discount d-flex justify-content-center"><span class="amount">5</span><span class="symbol">%</span></span>
                        <span class="stock__title">Скидка 5% на меж терминальную перевозку груза</span>
                        <span class="stock__duration"><i class="fa fa-calendar"></i>с 01.05.2016 по 31.08.2016</span>
                    </div>
                </div>
            </div>
            <div class="row row-item">
                <div class="col-12 col-sm-6">
                    <div class="stock__item d-flex flex-column justify-content-center">
                        <span class="stock__discount d-flex justify-content-center"><span class="amount">5</span><span class="symbol">%</span></span>
                        <span class="stock__title">Скидка 5% на меж терминальную перевозку груза Скидка 5% на меж терминальную перевозку груза Скидка 5% на меж терминальную перевозку груза</span>
                        <span class="stock__duration"><i class="fa fa-calendar"></i>с 01.05.2016 по 31.08.2016</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="stock__item d-flex flex-column justify-content-center">
                        <span class="stock__discount d-flex justify-content-center"><span class="amount">5</span><span class="symbol">%</span></span>
                        <span class="stock__title">Скидка 5% на меж терминальную перевозку груза</span>
                        <span class="stock__duration"><i class="fa fa-calendar"></i>с 01.05.2016 по 31.08.2016</span>
                    </div>
                </div>
            </div>
            <div class="row row-item">
                <div class="col-12 col-sm-6">
                    <div class="stock__item d-flex flex-column justify-content-center">
                        <span class="stock__discount d-flex justify-content-center"><span class="amount">5</span><span class="symbol">%</span></span>
                        <span class="stock__title">Скидка 5% на меж терминальную перевозку груза</span>
                        <span class="stock__duration"><i class="fa fa-calendar"></i>с 01.05.2016 по 31.08.2016</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="stock__item d-flex flex-column justify-content-center">
                        <span class="stock__discount d-flex justify-content-center"><span class="amount">5</span><span class="symbol">%</span></span>
                        <span class="stock__title">Скидка 5% на меж терминальную перевозку груза</span>
                        <span class="stock__duration"><i class="fa fa-calendar"></i>с 01.05.2016 по 31.08.2016</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection