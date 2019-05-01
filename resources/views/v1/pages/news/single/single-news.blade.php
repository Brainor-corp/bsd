@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/o-kompanii') }}" class="">О компании</a></span>
            <span class="breadcrumb__item"><a href="{{ route('news-list-show') }}">Новости</a></span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header>
                        <h1>{{ $post->title }}</h1><br>
                    </header>
                    <div class="row">
                        <div class="col-lg-8 col-12">
                            <span>
                                {{ html_entity_decode(strip_tags($post->content)) }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-8 mt-3">
                            <div class="d-flex news__info flex-wrap">
                                <span class="news__info-date">{{ \Carbon\Carbon::createFromTimeString($post->published_at)->format('d.m.Y') }}</span>
                                <span class="news__info-category">
                                    @foreach($post->terms->where('type', 'category') as $category)
                                        <a href="{{ route('news-list-show', ['categories' => [$category->id]]) }}">{{ $category->title . ($post->terms->where('type', 'tag')->count() ? ', ' : '') }}</a>
                                    @endforeach
                                    @foreach($post->terms->where('type', 'tag') as $tag)
                                        <a href="{{ route('news-list-show', ['cities' => [$tag->id]]) }}">{{ $tag->title . ($loop->last ? '' : ', ') }}</a>
                                    @endforeach
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection