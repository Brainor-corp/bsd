@extends('v1.layouts.innerPageLayout')

@section('headStyles')
    <link rel="stylesheet" href="{{ asset('v1/css/custom.css') }}">
@endsection

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/o-kompanii') }}" class="">О компании</a></span>
            <span class="breadcrumb__item">Отзывы</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Отзывы</h1>
                    </header>
                    @if($errors->has('g-recaptcha-response'))
                        <div class="row">
                            <div class="col-md-8 col-12">
                                <div class="alert alert-danger">
                                    Подтвердите, что Вы не робот.
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="comments">
                                @foreach($reviews as $review)
                                <div class="comments__item d-flex flex-nowrap flex-column flex-sm-row">
                                    <div class="comments__info d-flex flex-column">
                                        <span class="comments__title">{{ $review->author }}</span>
                                        <span class="comments__date">{{ $review->created_at->format('d.m.Y') }}</span>
                                        <span class="comments__city">{{ $review->city->name }}</span>
                                        @if($review->file)<a href="{{ url($review->file->base_url) }}" class="comments__scan"><i class="fa fa-eye"></i> Скан отзыва</a>@endif
                                    </div>
                                    <div class="comments__body">{{ $review->text }}</div>
                                </div>
                                @endforeach
                            </div>

                            <header class="wrapper__header">
                                <h2>Оставьте свой отзыв</h2>
                            </header>
                            @if(session('success'))
                                <div class="row">
                                    <div class="col-12 pt-2">
                                        <div class="alert alert-success">
                                            <ul>
                                                <li>Вы успешно оставили свой отзыв.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <form enctype="multipart/form-data" action="{{ route('save-review') }}" method="post" class="comment-form">
                                @csrf
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">ФИО или название компании</label>
                                    <div class="col">
                                        <input name="author" value="{{ old('author') }}" type="text" class="form-control" />
                                        @foreach ($errors->get('author') as $message)
                                            <span class="text-danger">{{ $message }}</span><br>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Город</label>
                                    <div class="col">
                                        <select required name="city" type="text" class="form-control">
                                            @foreach($cities as $city)
                                                <option @if(old('city') == $city->id) selected @elseif(isset($_COOKIE['current_city']) && $_COOKIE['current_city'] == $city->id) selected @endif value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                        @foreach ($errors->get('city') as $message)
                                            <span class="text-danger">{{ $message }}</span><br>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Телефон*</label>
                                    <div class="col calc__inpgrp">
                                        <input required name="phone" maxlength="20" value="{{ old('phone') }}" type="text" class="form-control" />
                                        @foreach ($errors->get('phone') as $message)
                                            <span class="text-danger">{{ $message }}</span><br>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">E-mail</label>
                                    <div class="col calc__inpgrp">
                                        <input name="email" type="email" value="{{ old('email') }}" class="form-control" />
                                        @foreach ($errors->get('email') as $message)
                                            <span class="text-danger">{{ $message }}</span><br>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Отзыв</label>
                                    <div class="col">
                                        <textarea name="text" class="form-control" maxlength="500" rows="3">{{ old('text') }}</textarea>
                                        @foreach ($errors->get('text') as $message)
                                            <span class="text-danger">{{ $message }}</span><br>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-item flex-column d-flex">
                                    <label for="review-files" class="link-attach"><span class="link-with-dotted">Прикрепить файл</span></label>
                                    @foreach ($errors->get('review-file') as $message)
                                        <span class="text-danger">{{ $message }}</span><br>
                                    @endforeach
                                    <input id="review-files" type="file" hidden name="review-file">
                                    <div id="file-name-wrapper" class="text-danger"></div>
                                </div>
                                <div class="form-item row">
                                    <div class="col-md-6">
                                        <div class="g-recaptcha" data-sitekey="{{ env('V2_GOOGLE_CAPTCHA_KEY') }}"></div>
                                    </div>
                                </div>
                                <div class="form-item d-flex">
                                    <button type="submit" class="btn btn-danger">Отправить отзыв</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footScripts')
    <script src="{{ asset('v1/js/inner.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
