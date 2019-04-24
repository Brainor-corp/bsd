<?php
/**
 * class: BRPageTemplate
 * title: Шаблон Отчетов
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
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>{{ $page->title }}</h1>
                    </header>
                    <div class="row">
                        <div class="col-12">
                            {!! html_entity_decode($page->content) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection