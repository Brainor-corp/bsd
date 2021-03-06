@extends('v1.layouts.innerPageLayout')

@section('headStyles')
    <link rel="stylesheet" href="{{ asset('packages/daterangepicker/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/bootstrap-select/css/bootstrap-select.css') }}">
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
                    <div class="row d-flex flex-column flex-lg-row">
                        <div class="col-lg-8 col-12">
                            <form id="filter-form" action="">
                                <div id="news-page__filter-block" class="sort__block d-flex flex-column flex-md-row align-items-center">
                                    <span class="sort__label margin-item">Фильтровать по:</span>
                                    <div class="input-group d-flex flex-column flex-md-row margin-item">
                                        <div class="input-group__item relative">
                                            <select name="categories[]" id="category_select"
                                                    class="filter-select" title="Категории"
                                                    data-selected-text-format="static" data-style="custom-select" data-width="100%"
                                                    multiple>
                                                @foreach($newsTerms as $term)
                                                    <option @if(isset($request->categories) && in_array($term->id, $request->categories)) selected @endif value="{{ $term->id }}">{{ $term->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group__item relative">
                                            <input class="custom-select" type="text" autocomplete="off" name="daterange"
                                                   value="{{ $request->daterange }}" placeholder="Дата"/>
                                        </div>
                                        <div class="input-group__item relative">
                                            <i class="dropdown-toggle fa-icon"></i>
                                            <select name="cities[]" id="city_select"
                                                    class="filter-select" title="Город"
                                                    data-live-search="true"
                                                    data-selected-text-format="static" data-style="custom-select" data-width="100%"
                                                    multiple>
                                                @foreach($cities as $city)
                                                    <option @if(isset($request->cities) && in_array($city->id, $request->cities)) selected @endif value="{{ $city->id }}">{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="filter-wrapper">
                                @include('v1.partials.news-page.news')
                            </div>
                        </div>
                        <div class="ml-lg-auto col-md-3 col-12 mt-3 mt-lg-0">
                            @php
                                $args = [
                                    'category'=>['sidebar-banner'],
                                    'order_by'=>['published_at', 'desc'],
                                    'type'=>'post',
                                ];
                                $banners = \Zeus\Admin\Cms\Helpers\CMSHelper::getQueryBuilder($args)->get();
                            @endphp
                            @foreach($banners as $banner)
                                <a href="{{ $banner->description ?? '#' }}" style="color:#5a666e">
                                    <div class="sidebar__block" style="margin-bottom: 15px">
                                        <div class="sidebar__image">
                                            <img src="{{ $banner->thumb ?? asset('/images/img/news-img.png') }}" alt="Фото баннера">
                                        </div>
                                        <div class="sidebar__body">
                                            <h5>{{ $banner->title ?? '' }}</h5>
                                            <span>{!! $banner->content ?? '' !!}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
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
