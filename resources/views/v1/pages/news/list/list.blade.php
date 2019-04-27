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
                                        <select id="category_select" class="custom-select">
                                            <option disabled selected>Категории</option>
                                            @foreach($newsTerms as $term)
                                                <option value="{{ $term->id }}">{{ $term->title }}</option>
                                            @endforeach
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
                                            <option disabled selected>Город</option>
                                            @foreach($cityTags as $city)
                                                <option value="{{ $city->id }}">{{ $city->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="tag-label-wrapper" class="selected-list flex-wrap d-flex">

                            </div>
                            <div class="news__block">
                                @foreach($posts as $post)
                                    <div class="news__item d-flex flex-column">
                                        <div>
                                            <span class="news__title">{{ $post->title }}</span>
                                        </div>
                                        <span class="news__content">{{ $post->description }}</span>
                                        <span class="news__info d-flex align-items-center">
                                            <span class="news__info-date">{{ \Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $post->published_at)->format('d F Y') }}</span>
                                            <span class="news__info-category">
                                                @foreach($post->terms->where('type', 'category') as $category)
                                                    {{ $category->title . ', '}}
                                                @endforeach
                                            </span>
                                        </span>
                                    </div>
                                @endforeach
                                {{ $posts->appends(request()->input())->links('v1.partials.pagination.pagination') }}
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
    <script src="{{ asset('v1/js/news.js') }}"></script>
@endsection