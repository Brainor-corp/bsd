<?php
/**
 * class: ZeusAdminPageTemplate
 * title: Шаблон с ссылками на детей
 */
?>

@extends('v1.layouts.innerPageLayout')

@section('styles')
@endsection

@section('headScripts')
@endsection

@section('page-title')
    {{$page->title}}
@endsection
@section('content')
    <section class="wrapper">
        {{--@php--}}
            {{--foreach ($page->ancestors as $ancestor)--}}
            {{--{--}}
                {{--$ancestors[] = $ancestor;--}}
            {{--}--}}
            {{--$ancestors[] = $page;--}}
        {{--@endphp--}}

        {{--<div class="breadcrumb__list d-flex">--}}
            {{--<div class="container">--}}
                {{--<span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>--}}
                {{--@foreach($ancestors as $ancestor)--}}
                    {{--<span class="breadcrumb__item"><a href="{{ url($ancestor->url) }}" class="">{{ $ancestor->title }}</a></span>--}}
                {{--@endforeach--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="container">--}}
            {{--<div class="my-5"><h1>{{ $page->title }}</h1></div>--}}
            {{--<div class="fs-text-post">--}}
                {{--{!! html_entity_decode($page->content) !!}--}}
            {{--</div>--}}
            {{--<div>--}}
                {{--<ul>--}}
                    {{--<div class="row align-items-center">--}}
                        {{--@foreach($page->children->sortByDesc('published_at') as $child)--}}
                            {{--<div class="col-12 mx-auto my-1 decoration-links">--}}
                                {{--<li><a href="{{ $child->url }}" class="fs-text-post my-2">{{ $child->title }}</a></li>--}}
                            {{--</div>--}}
                        {{--@endforeach--}}
                    {{--</div>--}}
                {{--</ul>--}}
            {{--</div>--}}
        {{--</div>--}}
    </section>
@endsection

@section('footScripts')
@endsection