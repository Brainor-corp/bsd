@extends('v1.layouts.mainPageLayout')

@section('headerStyles')
@endsection

@section('footerScripts')
    <script>
        var parameters={
            max_length:10,
            max_width:10,
            max_height:10,
            max_weight:10,
            max_volume:10,
        };
    </script>

    <script src="{{ asset('v1/js/short-calculator.js') }}@include('v1.partials.versions.jsVersion')"></script>
@endsection

@section('content')
<section class="service">
    <div class="container">
        <div class="title-inline d-flex align-items-center">
            <div class="margin-item"><h3>Услуги</h3></div>
            <a href="{{ url('/uslugi') }}" class="link-with-dotted margin-item">Все услуги</a>
        </div>
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
<section class="about-company bg-dark mb-0">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-7">
                <div class="title-inline d-flex align-items-center">
                    <div class="whiteTxtColor margin-item"><h3>О компании</h3></div>
                    <a class="margin-item darkTxtColor link-with-dotted" href="{{ url('/o-kompanii') }}">Подробнее</a>
                </div>
                <p class="darkTxtColor font-medium mw-95 mb-10">ООО «Балтийская Служба Доставки» заслужила репутацию честного и выгодного партнера на Российском рынке грузоперевозок, что важно для любой компании. Одна из основных задач, которые мы перед собой ставим - минимизация времени, необходимого для оформления груза перед отправкой.</p>
                <div class="clearfix mb-10">
                    <p class="playfairdisplay d-block float-left font-medium p_ourwork">Наша работа - решение проблем по доставке Вашего груза!</p>
                    <p class="d-block float-left darkTxtColor pr-50 p_ourfils">Наши филиалы находятся в городах: Санкт-Петербург, Москва, Нижний Новгород, Ростов-на-Дону, Таганрог, Астрахань, Волгоград, Краснодар, Пятигорск</p>
                </div>
                <p class="darkTxtColor font-medium mw-95">Компания ООО «БСД» также осуществляет автоматическую доставку груза до дверей клиента по Ростовской области, Краснодарскому, Ставропольскому краям и по Северному Кавказу.</p>
            </div>
            <div class="col-sm-12 col-md-5 d-flex">
                <ul class="list-unsetyled stat_list pt-40 d-flex flex-column">
                    <li class="stat__item d-flex align-items-center">
                        <span class="stat__item-amount margin-item">9</span>
                        <span class="margin-item">лет занимаеимся<br />грузоперевозками</span>
                    </li>
                    <li class="stat__item d-flex align-items-center">
                        <span class="stat__item-amount margin-item">114</span>
                        <span class="margin-item">сотрудников<br />работает в штате</span>
                    </li>
                    <li class="stat__item d-flex align-items-center">
                        <span class="stat__item-amount margin-item">316</span>
                        <span class="margin-item">доставляем грузы<br />по 316 городам</span>
                    </li>
                    <li class="stat__item d-flex align-items-center">
                        <span class="stat__item-amount margin-item">2 678</span>
                        <span class="margin-item">терминалов по<br />всей России</span>
                    </li>
                    <li class="stat__item d-flex align-items-center">
                        <span class="stat__item-amount margin-item">5 682</span>
                        <span class="margin-item">раза выполнили<br />доставку</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection