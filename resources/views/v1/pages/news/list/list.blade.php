@extends('v1.layouts.innerPageLayout')

@section('headStyles')
    <link rel="stylesheet" href="{{ asset('packages/daterangepicker/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/bootstrap-select/css/bootstrap-select.css') }}">
    {{--    <link rel="stylesheet" href="{{ asset('v1/css/custom.css') }}">--}}
@endsection

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
                            <form id="filter-form" action="">
                                <div id="news-page__filter-block" class="sort__block d-flex align-items-center">
                                    <span class="sort__label margin-item">Сортировать по:</span>
                                    <div class="input-group d-flex margin-item">
                                        <div class="input-group__item relative">
                                            <select name="category" id="category_select" data-width="215px"
                                                    class="filter-select" title="Категории"
                                                    data-selected-text-format="static" data-style="custom-select"
                                                    multiple>
                                                @foreach($newsTerms as $term)
                                                    <option value="{{ $term->id }}">{{ $term->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group__item relative">
                                            <input class="custom-select" type="text" autocomplete="off" name="daterange"
                                                   value="" placeholder="Дата"/>
                                        </div>
                                        <div class="input-group__item relative">
                                            <i class="dropdown-toggle fa-icon"></i>
                                            <select name="city" id="city_select" data-width="215px"
                                                    class="filter-select" title="Город"
                                                    data-selected-text-format="static" data-style="custom-select"
                                                    multiple>
                                                @foreach($cityTags as $city)
                                                    <option value="{{ $city->id }}">{{ $city->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="tag-label-wrapper" class="selected-list flex-wrap d-flex"></div>
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
    <script src="{{ asset('packages/daterangepicker/js/moment.min.js') }}"></script>
    <script src="{{ asset('packages/daterangepicker/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('packages/bootstrap-select/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('v1/js/news.js') }}"></script>
@endsection