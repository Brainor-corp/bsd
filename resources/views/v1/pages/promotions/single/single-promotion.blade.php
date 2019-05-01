@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/o-kompanii') }}" class="">О компании</a></span>
            <span class="breadcrumb__item"><a href="{{ route('promotion-list-show') }}">Акции</a></span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container stock_list">
            <header class="wrapper__header">
                <h1>{{ $promotion->title }}</h1>
            </header>
            <div class="row">
                <div class="col-lg-8 col-12">
                    <span>
                        {{ $promotion->text ?? '' }}
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-8 mt-3">
                    <div class="d-flex news__info flex-wrap">
                        <span class="stock__duration"><i class="fa fa-calendar"></i>с {{ $promotion->c_start_at->format('d.m.Y') }} по {{ $promotion->c_end_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection