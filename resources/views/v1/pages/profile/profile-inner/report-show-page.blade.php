@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/klientam') }}" class="">Клиентам</a></span>
            <span class="breadcrumb__item"><a href="{{ route('report-list') }}" class="">Отчеты</a></span>
            <span class="breadcrumb__item">Отчет</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">

            </div>
        </div>
    </section>

@endsection

@section('footScripts')
    <script src="{{ asset('v1/js/profile.js') }}"></script>
@endsection