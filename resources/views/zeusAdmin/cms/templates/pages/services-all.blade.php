<?php
/**
 * class: ZeusAdminPageTemplate
 * title: Шаблон страницы-все-услуги
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
    <section class="service">
        <div class="container">
            <div class="row row-item justify-content-md-center">
                <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                    <div class="service__block d-flex relative mezhter">
                        <div class="service__block_body">
                            <div class="service__block_title">Меж-терминальная перевозка</div>
                            <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                    <div class="service__block d-flex relative aviap">

                        <div class="service__block_body">
                            <div class="service__block_title"><a href="#">Авиаперевозка</a></div>
                            <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-item justify-content-md-center">
                <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                    <div class="service__block d-flex relative perevozkadoc">
                        <div class="service__block_body">
                            <div class="service__block_title">Доставка документов</div>
                            <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                    <div class="service__block d-flex relative dostavkavgip">
                        <div class="service__block_body">
                            <div class="service__block_title">Доставка в гипермаркеты</div>
                            <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection