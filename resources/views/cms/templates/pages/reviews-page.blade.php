<?php
/**
 * class: BRPageTemplate
 * title: Шаблон страницы отзывов
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
                            <div class="my-5"><h1>Отзывы</h1></div>
                            <div class="fs-text-post">
                                <div class="row">
                                    @php
                                        $dateFrom = app('request')->input('filterDateFrom');
                                        $dateTo = app('request')->input('filterDateTo');
                                        $args = [
                                            'type' => 'post',
                                            'category' => ['otzyvy']
                                        ];
                                        $reviews = \Bradmin\Cms\Helpers\CMSHelper::getQueryBuilder($args)

                                        ->paginate(10);
                                    @endphp
                                    @foreach($reviews as $item)
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
                                                    <span>{!! ($item->description) !!}</span>
                                                    <div class="my-4">
                                                        {{ str_limit(strip_tags(html_entity_decode($item->content)), 200) }}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                    <div class="col-12 mt-3">
                                        <div class="row justify-content-center">
                                            <div class="col-auto page-link-no-focus">
                                                {{ $reviews->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-12 main-content">
                @include('v1.partials.sidebar.sidebar-default')
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