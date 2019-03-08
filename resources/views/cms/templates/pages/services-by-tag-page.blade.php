<?php
/**
 * class: BRPageTemplate
 * title: Шаблон с услугами по метке
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

@php
    $tag = app('request')->input('tag');
    if(isset($tag)){
        $tilePage = \Bradmin\Cms\Models\BRPost::where('slug', $tag)->first();
    }
@endphp

@section('page-title')
    @if(isset($tilePage))
        {{$tilePage->title}}
    @endif
@endsection
@section('content')
    <div class="container">
        <div class="row my-5">
            <div class="col-lg-9 col-md-8 col-12">
                <div class="align-items-start">
                    <div class="text-justify">
                        @if(isset($tilePage))
                            <h1>{{$tilePage->title}}</h1>
                            @php
                                $services = \Bradmin\Cms\Models\BRPost::where('type', 'page')
                                ->whereHas('tags', function ($q) use ($tag){
                                    $q->where('slug', $tag);
                                })
                                ->get();
                            @endphp
                            <ul class="mt-4">
                                <div class="row align-items-center">
                                    @foreach($services as $service)
                                        <div class="col-12 mx-auto my-1 decoration-links">
                                            <li><a href="{{$service->url}}" class="fs-text-post my-2">{{ $service->title }}</a></li>
                                        </div>
                                    @endforeach
                                </div>
                            </ul>
                        @else
                            <h1>Страницы с такой меткой нет</h1>
                        @endif
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
    <script type="text/javascript" src="{{ asset('v1/plugins/multiselect/dist/js/selectize.js') }}"></script>
@endsection