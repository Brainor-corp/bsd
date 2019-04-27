@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/o-kompanii') }}" class="">О компании</a></span>
            <span class="breadcrumb__item">Документы</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <header class="wrapper__header">
                        <h1>Документы и сертификаты</h1>
                    </header>
                    @foreach($documentTypes as $documentType)
                        <section class="documentation__list">
                            <header>
                                <h2>{{ $documentType->title }}</h2>
                            </header>
                            @foreach($documentType->files as $file)
                                <span class="documentation__item d-flex">
                                    <div class="documentation__icon margin-item">
                                        <i class="icons pdf-icon"></i>
                                    </div>
                                    <div class="documentation__info margin-item">
                                        <a href="{{ url($file->url) }}" class="documentation__name margin-item">{{ $file->title ?? basename(url($file->url)) }}</a>
                                        <span class="documentation__size margin-item">{{ round($file->size / 1024 / 1024, 2) }}Мб</span>
                                    </div>
                                </span>
                            @endforeach
                        </section>
                    @endforeach
                </div>
                <div class="col-4">
                    <div class="side-image">
                        <img src="{{ asset('images/documentation-bg.png') }}" alt="О нас">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection