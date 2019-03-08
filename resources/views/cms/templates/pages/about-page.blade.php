<?php
/**
 * class: BRPageTemplate
 * title: Шаблон страницы "О нас"
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
        {!! html_entity_decode($page->content) !!}
        <div class="workers">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <header>
                            <h2>Сотрудники</h2>
                        </header>
                        <div id="workers-slider" class="carousel slide" data-ride="carousel" data-interval="9000">
                            <div class="carousel-inner row w-100 mx-auto" role="listbox">
                                <div class="worker__item carousel-item col-md-2 active">
                                    <div class="worker__inner d-flex flex-column">
                                        <div class="worker__foto">
                                            <div class="worker__square">&nbsp;</div>
                                            <img src="{{ asset('/images/img/worker-1.png') }}" alt="Бабкин Андрей Сергеевич">
                                        </div>
                                        <span class="worker__name">Бабкин Андрей Сергеевич</span>
                                        <span class="worker__position">Генеральный директор</span>
                                        <span class="worker__contacts d-flex flex-column">
                                            <span class="worker__contacts-item"><i class="fa fa-envelope-o"></i><a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="worker__item carousel-item col-md-2">
                                    <div class="worker__inner d-flex flex-column">
                                        <div class="worker__foto">
                                            <div class="worker__square">&nbsp;</div>
                                            <img src="{{ asset('/images/img/worker-1.png') }}" alt="Ковалёв Александр Петрович">
                                        </div>
                                        <span class="worker__name">Ковалёв Александр Петрович</span>
                                        <span class="worker__position">Исполнительный директор</span>
                                        <span class="worker__contacts d-flex flex-column">
                                            <span class="worker__contacts-item"><i class="fa fa-envelope-o"></i><a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a></span>
                                            <span class="worker__contacts-item"><i class="fa fa-phone"></i><a href="tel:+74966777667">+7(496)677-76-67</a></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="worker__item carousel-item col-md-2">
                                    <div class="worker__inner d-flex flex-column">
                                        <div class="worker__foto">
                                            <div class="worker__square">&nbsp;</div>
                                            <img src="{{ asset('/images/img/worker-1.png') }}" alt="Сидорова Наталья Владимировна">
                                        </div>
                                        <span class="worker__name">Сидорова Наталья Владимировна</span>
                                        <span class="worker__position">Менеджер по приемке грузов</span>
                                        <span class="worker__contacts d-flex flex-column">
                                            <span class="worker__contacts-item"><i class="fa fa-envelope-o"></i><a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a></span>
                                            <span class="worker__contacts-item"><i class="fa fa-phone"></i><a href="tel:+74966777667">+7(496)677-76-67</a></span>
                                            <span class="worker__contacts-item"><i class="fa fa-skype"></i><a href="skype:skypename?call">skypename</a></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="worker__item carousel-item col-md-2">
                                    <div class="worker__inner d-flex flex-column">
                                        <div class="worker__foto">
                                            <div class="worker__square">&nbsp;</div>
                                            <img src="{{ asset('/images/img/worker-1.png') }}" alt="Бабкин Андрей Сергеевич">
                                        </div>
                                        <span class="worker__name">Бабкин Андрей Сергеевич</span>
                                        <span class="worker__position">Генеральный директор</span>
                                        <span class="worker__contacts d-flex flex-column">
                                            <span class="worker__contacts-item"><i class="fa fa-envelope-o"></i><a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="worker__item carousel-item col-md-2">
                                    <div class="worker__inner d-flex flex-column">
                                        <div class="worker__foto">
                                            <div class="worker__square">&nbsp;</div>
                                            <img src="{{ asset('/images/img/worker-1.png') }}" alt="Ковалёв Александр Петрович">
                                        </div>
                                        <span class="worker__name">Ковалёв Александр Петрович</span>
                                        <span class="worker__position">Исполнительный директор</span>
                                        <span class="worker__contacts d-flex flex-column">
                                            <span class="worker__contacts-item"><i class="fa fa-envelope-o"></i><a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a></span>
                                            <span class="worker__contacts-item"><i class="fa fa-phone"></i><a href="tel:+74966777667">+7(496)677-76-67</a></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="worker__item carousel-item col-md-2">
                                    <div class="worker__inner d-flex flex-column">
                                        <div class="worker__foto">
                                            <div class="worker__square">&nbsp;</div>
                                            <img src="{{ asset('/images/img/worker-1.png') }}" alt="Бабкин Андрей Сергеевич">
                                        </div>
                                        <span class="worker__name">Бабкин Андрей Сергеевич</span>
                                        <span class="worker__position">Генеральный директор</span>
                                        <span class="worker__contacts d-flex flex-column">
                                            <span class="worker__contacts-item"><i class="fa fa-envelope-o"></i><a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="worker__item carousel-item col-md-2">
                                    <div class="worker__inner d-flex flex-column">
                                        <div class="worker__foto">
                                            <div class="worker__square">&nbsp;</div>
                                            <img src="{{ asset('/images/img/worker-1.png') }}" alt="Ковалёв Александр Петрович">
                                        </div>
                                        <span class="worker__name">Ковалёв Александр Петрович</span>
                                        <span class="worker__position">Исполнительный директор</span>
                                        <span class="worker__contacts d-flex flex-column">
                                            <span class="worker__contacts-item"><i class="fa fa-envelope-o"></i><a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a></span>
                                            <span class="worker__contacts-item"><i class="fa fa-phone"></i><a href="tel:+74966777667">+7(496)677-76-67</a></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="worker__item carousel-item col-md-2">
                                    <div class="worker__inner d-flex flex-column">
                                        <div class="worker__foto">
                                            <div class="worker__square">&nbsp;</div>
                                            <img src="{{ asset('/images/img/worker-1.png') }}" alt="Бабкин Андрей Сергеевич">
                                        </div>
                                        <span class="worker__name">Бабкин Андрей Сергеевич</span>
                                        <span class="worker__position">Генеральный директор</span>
                                        <span class="worker__contacts d-flex flex-column">
                                            <span class="worker__contacts-item"><i class="fa fa-envelope-o"></i><a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="worker__item carousel-item col-md-2">
                                    <div class="worker__inner d-flex flex-column">
                                        <div class="worker__foto">
                                            <div class="worker__square">&nbsp;</div>
                                            <img src="{{ asset('/images/img/worker-1.png') }}" alt="Ковалёв Александр Петрович">
                                        </div>
                                        <span class="worker__name">Ковалёв Александр Петрович</span>
                                        <span class="worker__position">Исполнительный директор</span>
                                        <span class="worker__contacts d-flex flex-column">
                                            <span class="worker__contacts-item"><i class="fa fa-envelope-o"></i><a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a></span>
                                            <span class="worker__contacts-item"><i class="fa fa-phone"></i><a href="tel:+74966777667">+7(496)677-76-67</a></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="worker__item carousel-item col-md-2">
                                    <div class="worker__inner d-flex flex-column">
                                        <div class="worker__foto">
                                            <div class="worker__square">&nbsp;</div>
                                            <img src="{{ asset('/images/img/worker-1.png') }}" alt="Сидорова Наталья Владимировна">
                                        </div>
                                        <span class="worker__name">Сидорова Наталья Владимировна</span>
                                        <span class="worker__position">Менеджер по приемке грузов</span>
                                        <span class="worker__contacts d-flex flex-column">
                                            <span class="worker__contacts-item"><i class="fa fa-envelope-o"></i><a href="mailto:office@tk-bsd.com">office@tk-bsd.com</a></span>
                                            <span class="worker__contacts-item"><i class="fa fa-phone"></i><a href="tel:+74966777667">+7(496)677-76-67</a></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#workers-slider" role="button" data-slide="prev">
                                <i class="fa fa-chevron-left fa-lg text-muted"></i>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next text-faded" href="#workers-slider" role="button" data-slide="next">
                                <i class="fa fa-chevron-right fa-lg text-muted"></i>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="certificates">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <header>
                            <h2>Сотрудники</h2>
                        </header>
                        <div id="certificates-slider" class="carousel slide" data-ride="carousel" data-interval="9000">
                            <div class="carousel-inner row model-carousel w-100 mx-auto" role="listbox">
                                <div class="certificate__item carousel-item col-md-2 active">
                                    <div class="certificate__mask">
                                        <div class="certificate__mask-icon d-flex justify-content-center align-items-center"><i class="fa fa-2x fa-search"></i></div>
                                    </div>
                                    <img class="thumbnail" src="{{ asset('/images/img/certificate-img.png') }}" alt="">
                                </div>
                                <div class="certificate__item carousel-item col-md-2">
                                    <div class="certificate__mask">
                                        <div class="certificate__mask-icon d-flex justify-content-center align-items-center"><i class="fa fa-2x fa-search"></i></div>
                                    </div>
                                    <img class="thumbnail" src="{{ asset('/images/img/certificate-img.png') }}" alt="">
                                </div>
                                <div class="certificate__item carousel-item col-md-2">
                                    <div class="certificate__mask">
                                        <div class="certificate__mask-icon d-flex justify-content-center align-items-center"><i class="fa fa-2x fa-search"></i></div>
                                    </div>
                                    <img class="thumbnail" src="{{ asset('/images/img/certificate-img.png') }}" alt="">
                                </div>
                                <div class="certificate__item carousel-item col-md-2">
                                    <div class="certificate__mask">
                                        <div class="certificate__mask-icon d-flex justify-content-center align-items-center"><i class="fa fa-2x fa-search"></i></div>
                                    </div>
                                    <img class="thumbnail" src="{{ asset('/images/img/certificate-img.png') }}" alt="">
                                </div>
                                <div class="certificate__item carousel-item col-md-2">
                                    <div class="certificate__mask">
                                        <div class="certificate__mask-icon d-flex justify-content-center align-items-center"><i class="fa fa-2x fa-search"></i></div>
                                    </div>
                                    <img class="thumbnail" src="{{ asset('/images/img/certificate-img.png') }}" alt="">
                                </div>
                                <div class="certificate__item carousel-item col-md-2">
                                    <div class="certificate__mask">
                                        <div class="certificate__mask-icon d-flex justify-content-center align-items-center"><i class="fa fa-2x fa-search"></i></div>
                                    </div>
                                    <img class="thumbnail" src="{{ asset('/images/img/certificate-img.png') }}" alt="">
                                </div>
                                <div class="certificate__item carousel-item col-md-2">
                                    <div class="certificate__mask">
                                        <div class="certificate__mask-icon d-flex justify-content-center align-items-center"><i class="fa fa-2x fa-search"></i></div>
                                    </div>
                                    <img class="thumbnail" src="{{ asset('/images/img/certificate-img.png') }}" alt="">
                                </div>
                                <div class="certificate__item carousel-item col-md-2">
                                    <div class="certificate__mask">
                                        <div class="certificate__mask-icon d-flex justify-content-center align-items-center"><i class="fa fa-2x fa-search"></i></div>
                                    </div>
                                    <img class="thumbnail" src="{{ asset('/images/img/certificate-img.png') }}" alt="">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#certificates-slider" role="button" data-slide="prev">
                                <i class="fa fa-chevron-left fa-lg text-muted"></i>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next text-faded" href="#certificates-slider" role="button" data-slide="next">
                                <i class="fa fa-chevron-right fa-lg text-muted"></i>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="company-figures bg-dark">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <header class="d-flex align-items-center">
                            <div class="whiteTxtColor margin-item"><h3>Цифры компании</h3></div>
                        </header>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-3">
                        <div class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount stat__item-amount-big margin-item">9</span>
                            <span class="margin-item">лет занимаеимся<br />грузоперевозками</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">114</span>
                            <span class="margin-item">сотрудников<br />работает в штате</span>
                        </div>
                        <div class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">316</span>
                            <span class="margin-item">доставляем грузы<br />по 316 городам</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">2 678</span>
                            <span class="margin-item">терминалов по<br />всей России</span>
                        </div>
                        <div class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">5 682</span>
                            <span class="margin-item">раза выполнили<br />доставку</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">2 678</span>
                            <span class="margin-item">терминалов по<br />всей России</span>
                        </div>
                        <div class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">5 682</span>
                            <span class="margin-item">раза выполнили<br />доставку</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mission">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <header class="d-flex align-items-center">
                            <div class="whiteTxtColor margin-item"><h3>Миссия</h3></div>
                        </header>
                    </div>
                </div>
                <div class="row">
                    <div class="col-7">
                        <p class="playfairdisplay whiteTxtColor font-medium">Наша работа - решение проблем по доставке Вашего груза!</p>
                        <p class="darkTxtColor">ООО «Балтийская Служба Доставки» заслужила репутацию честного и выгодного партнера на Российском рынке грузоперевозок, что важно для любой компании. Одна из основных задач, которые мы перед собой ставим - минимизация времени, необходимого для оформления груза перед отправкой.</p>
                        <p class="darkTxtColor">Наши филиалы находятся в городах: Санкт-Петербург, Москва, Нижний Новгород, Ростов-на-Дону, Таганрог, Астрахань, Волгоград, Краснодар, Пятигорск</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="service">
            <div class="container">
                <header class="title-inline d-flex align-items-center">
                    <div class="margin-item"><h3>Услуги</h3></div>
                    <a href="##" class="link-with-dotted margin-item">Все услуги</a>
                </header>
                <div class="row row-item">
                    <div class="col-12 col-sm-6">
                        <div class="service__block d-flex relative mezhter">
                            <div class="service__block_body">
                                <div class="service__block_title">Меж-терминальная перевозка</div>
                                <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="service__block d-flex relative aviap">

                            <div class="service__block_body">
                                <div class="service__block_title"><a href="#">Авиаперевозка</a></div>
                                <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-item">
                    <div class="col-12 col-sm-6">
                        <div class="service__block d-flex relative perevozkadoc">
                            <div class="service__block_body">
                                <div class="service__block_title">Доставка документов</div>
                                <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="service__block d-flex relative dostavkavgip">
                            <div class="service__block_body">
                                <div class="service__block_title">Доставка в гипермаркеты</div>
                                <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade modal-profile" tabindex="-1" role="dialog" aria-labelledby="modalProfile" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">×</button>
                    <h3 class="modal-title"></h3>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                </div>
            </div>
        </div>
    </div>


@endsection

@section('footerScripts')
    <script>
        $(function () {
            $('#workers-slider').on('slide.bs.carousel', function (e) {

                var $e = $(e.relatedTarget);
                var idx = $e.index();
                var itemsPerSlide = 6;
                var totalItems = $('#workers-slider .carousel-item').length;

                if (idx >= totalItems-(itemsPerSlide-1)) {
                    var it = itemsPerSlide - (totalItems - idx);
                    for (var i = 0; i < it; i++) {
                        // append slides to end
                        if (e.direction=="left") {
                            $('#workers-slider .carousel-item').eq(i).appendTo('#workers-slider .carousel-inner');
                        }
                        else {
                            $('#workers-slider .carousel-item').eq(0).appendTo('#workers-slider .carousel-inner');
                        }
                    }
                }
            });

            $('#certificates-slider').on('slide.bs.carousel', function (e) {

                var $e = $(e.relatedTarget);
                var idx = $e.index();
                var itemsPerSlide = 6;
                var totalItems = $('#certificates-slider .carousel-item').length;

                if (idx >= totalItems-(itemsPerSlide-1)) {
                    var it = itemsPerSlide - (totalItems - idx);
                    for (var i = 0; i < it; i++) {
                        if (e.direction=="left") {
                            $('#certificates-slider .carousel-item').eq(i).appendTo('#certificates-slider .carousel-inner');
                        }
                        else {
                            $('#certificates-slider .carousel-item').eq(0).appendTo('#certificates-slider .carousel-inner');
                        }
                    }
                }
            });

            /* show lightbox when clicking a thumbnail */
            $('.certificate__item').click(function(event){
                event.preventDefault();
                var content = $('.modal-body');
                content.empty();
                var title = $(this).attr("title");
                $('.modal-title').html(title);
                content.html($(this).html());
                $(".modal-profile").modal({show:true});
            });

        })
    </script>
@endsection