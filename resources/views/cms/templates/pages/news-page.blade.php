<?php
/**
 * class: BRPageTemplate
 * title: Шаблон страницы новостей
 */
?>

@extends('v1.layouts.inside-page-layout')

@section('styles')
    <link href='{{ asset("v1/fonts/Raleway/raleway.css") }}' rel='stylesheet' type='text/css'>
    <link href="{{ asset("v1/css/pages/inside-page/styles/inside-page.css") }}" rel="stylesheet">
    <link href="{{ asset("v1/css/pages/inside-page/media/inside-page-media.css") }}" rel="stylesheet">
    <link href="{{ asset("v1/plugins/multiselect/dist/css/selectize.css") }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('v1/plugins/datepicker/css/bootstrap-datepicker.css')}}@include('v1.partials.versions.css')">
@endsection

@section('headScripts')
@endsection

@section('page-title')
    {{$page->title}}
@endsection
@section('content')
    <div class="container">
        <div class="row inside-content-margin">
            <div class="col-lg-9 col-md-8 col-12">
                <div class="align-items-start">
                    <div class="text-justify">
                        <div class="text-justify">
                            {{--<div class="mb-5 fs-breadcrumbs decoration-links"><a href="#">Bread</a>/<a href="#">crumbs</a></div>--}}
                            <div class="my-5"><h1>Новости</h1></div>
                            <div class="fs-text-post">
                                <div class="row">
                                    @php
                                        $dateFrom = app('request')->input('filterDateFrom');
                                        $dateTo = app('request')->input('filterDateTo');
                                        $args = [
                                            'type' => 'post',
                                            'category' => ['novosti']
                                        ];
                                        $news = \Bradmin\Cms\Helpers\CMSHelper::getQueryBuilder($args)
                                        ->when(isset($dateFrom), function ($query) use ($dateFrom){
                                            return $query->where('published_at', '>=', $dateFrom);
                                        })
                                        ->when(isset($dateTo), function ($query) use ($dateTo){
                                            return $query->where('published_at', '<=', date('Y-m-d', strtotime($dateTo. ' + 1 days')));
                                        })
                                        ->paginate(10);
                                    @endphp
                                    @foreach($news as $item)
                                        <div class="col-lg-12 col-md-12 col-12 my-4 link-cards">
                                            <a class="card-link" href="{{ $item->url }}">
                                                <div class="bg-white boxshadow-cards rounded p-3 news-cards">
                                                    <div class="d-flex justify-content-between flex-wrap mb-2">
                                                        <h4 class="mr-3">
                                                            <a class="text-black-50" href="{{ $item->url }}">{{ $item->title }}</a>
                                                        </h4>
                                                        <div class="news-date">
                                                            <span class="color-gray"><i class="fas fa-calendar-alt mr-3"></i>{{ date('Y-m-d', strtotime($item->published_at)) }}</span>
                                                        </div>
                                                    </div>
                                                    <span>{{ $item->description }}</span>
                                                    <div class="my-4">
                                                        {{ str_limit(strip_tags($item->content), 200) }}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                    <div class="col-12 mt-3">
                                        <div class="row justify-content-center">
                                            <div class="col-auto page-link-no-focus">
                                                {{ $news->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="col-12">--}}
                    {{--<nav aria-label="Page navigation example">--}}
                        {{--<ul class="pagination justify-content-center">--}}
                            {{--<li class="page-item">--}}
                                {{--<a class="page-link text-dark" href="#" aria-label="Previous">--}}
                                    {{--<span aria-hidden="true">&laquo;</span>--}}
                                    {{--<span class="sr-only">Previous</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li class="page-item"><a class="page-link text-dark" href="#">1</a></li>--}}
                            {{--<li class="page-item"><a class="page-link text-dark" href="#">2</a></li>--}}
                            {{--<li class="page-item"><a class="page-link text-dark" href="#">3</a></li>--}}
                            {{--<li class="page-item">--}}
                                {{--<a class="page-link text-dark" href="#" aria-label="Next">--}}
                                    {{--<span aria-hidden="true">&raquo;</span>--}}
                                    {{--<span class="sr-only">Next</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</nav>--}}
                {{--</div>--}}
            </div>
            <div class="col-lg-3 col-md-4 col-12 main-content">
                @include('v1.partials.sidebar.sidebar-news')
            </div>
        </div>
    </div>
@endsection

@section('footScripts')
    <script type="text/javascript" src="{{ asset('v1/js/sidebar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v1/js/news-page.js') }}"></script>
    {{--<script type="text/javascript" src="{{ asset('v1/js/selectize.js') }}"></script>--}}
    {{--<script type="text/javascript" src="{{ asset('v1/plugins/multiselect/dist/js/standalone/selectize.js') }}"></script>--}}
    <script src="{{ asset('v1/plugins/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('v1/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js')}}"></script>
@endsection