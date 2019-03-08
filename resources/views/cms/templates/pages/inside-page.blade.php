<?php
/**
 * class: BRPageTemplate
 * title: Шаблон внутренней страницы
 */
?>

@extends('v1.layouts.inside-page-layout')

@section('styles')
    <link href='{{ asset("v1/fonts/Raleway/raleway.css") }}' rel='stylesheet' type='text/css'>
    <link href="{{ asset("v1/css/pages/inside-page/styles/inside-page.css") }}" rel="stylesheet">
    <link href="{{ asset("v1/css/pages/inside-page/media/inside-page-media.css") }}" rel="stylesheet">
    <link href="{{ asset("v1/plugins/multiselect/dist/css/selectize.css") }}" rel="stylesheet">
@endsection

@section('headScripts')
@endsection

@section('page-title')
    {{$page->title}}
@endsection
@section('content')
    <div class="container mt-5">
        <div class="row my-5">
            <div class="col-lg-9 col-md-8 col-12">
                <div class="align-items-start">
                    <div class="text-justify">
                        <div class="text-justify">
                            <div class="mb-5 fs-breadcrumbs decoration-links">
                                @php
                                    foreach ($page->ancestors as $ancestor)
                                    {
                                        $ancestors[] = $ancestor;
                                    }
                                    $ancestors[] = $page;
                                @endphp
                                <a href="/"><i class="fas fa-home"></i></a>
                                @foreach($ancestors as $ancestor)
                                    /<a href="{{ url($ancestor->url) }}">{{ $ancestor->title }}</a>
                                @endforeach
                            </div>
                            <div class="my-5"><h1>{{ $page->title }}</h1></div>
                            <div class="fs-text-post">
                                {!! html_entity_decode($page->content) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-12 main-content">
                @include('v1.partials.sidebar.sidebar-pages')
            </div>
        </div>
    </div>
@endsection

@section('footScripts')
    <script type="text/javascript" src="{{ asset('v1/js/sidebar.js') }}"></script>
    {{--<script type="text/javascript" src="{{ asset('v1/plugins/multiselect/dist/js/selectize.js') }}"></script>--}}
@endsection