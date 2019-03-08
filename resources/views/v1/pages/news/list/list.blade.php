@extends('v1.layouts.innerPageLayout')


@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/o-kompanii') }}" class="">О компании</a></span>
            <span class="breadcrumb__item">Новости</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Новости</h1>
                    </header>
                    <div class="row">
                        <div class="col-8">
                            <div class="sort__block d-flex align-items-center">
                                <span class="sort__label margin-item">Сортировать по:</span>
                                <div class="input-group d-flex margin-item">
                                    <div class="input-group__item relative">
                                        <i class="dropdown-toggle fa-icon"></i>
                                        <select class="custom-select">
                                            <option disabled selected>Категории</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                        </select>
                                    </div>
                                    <div class="input-group__item relative">
                                        <i class="dropdown-toggle fa-icon"></i>
                                        <select class="custom-select">
                                            <option disabled selected>Дата</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                        </select>
                                    </div>
                                    <div class="input-group__item relative">
                                        <i class="dropdown-toggle fa-icon"></i>
                                        <select class="custom-select">
                                            <option disabled selected>Санкт-Петербург</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="selected-list d-flex">
                                <div class="selected-item d-flex align-items-center margin-item">
                                    <span class="selected-item__name">Санкт-Петербург</span>
                                    <i class="fa fa-close"></i>
                                </div>
                                <div class="selected-item d-flex align-items-center margin-item">
                                    <span class="selected-item__name">По дате</span>
                                    <i class="fa fa-close"></i>
                                </div>
                            </div>
                            <div class="news__block">
                                <div class="news__item d-flex flex-column">
                                    <div>
                                        <span class="news__title">Возможны задержки автоперевозок в Камышине, Саратове и Энгельсе</span>
                                    </div>
                                    <span class="news__content">Из-за сильного снегопада возможны задержки межтерминальной перевозки грузов в Камышин, Саратов и Энгельс, а также по всем направлениям из этих городов.</span>
                                    <span class="news__info d-flex align-items-center">
                                        <span class="news__info-date">26 декабря 2018</span>
                                        <span class="news__info-category">Региональные новости, Новости компании</span>
                                    </span>
                                </div>
                                <div class="news__item d-flex flex-column">
                                    <div>
                                        <span class="news__title">Возможны задержки автоперевозок в Камышине, Саратове и Энгельсе</span>
                                    </div>
                                    <span class="news__content">Из-за сильного снегопада возможны задержки межтерминальной перевозки грузов в Камышин, Саратов и Энгельс, а также по всем направлениям из этих городов.</span>
                                    <span class="news__info d-flex align-items-center">
                                        <span class="news__info-date">26 декабря 2018</span>
                                        <span class="news__info-category">Региональные новости, Новости компании</span>
                                    </span>
                                </div>
                                <ul class="news__pagination pagination">
                                    <li class="page-item"><a class="page-link" href="#">Назад</a></li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                                    <li class="page-item"><a class="page-link" href="#">21</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Далее</a></li>
                                </ul>
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