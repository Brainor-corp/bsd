@extends('v1.layouts.innerPageLayout')


@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="##" class="">Личный кабинет</a></span>
            <span class="breadcrumb__item">Договор</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @include('v1.partials.profile.profile-tabs')
                    <header class="wrapper__header">
                        <h1>Договор</h1>
                    </header>
                    <div class="row">
                        <div class="col-4">
                            <div class="row">
                                @if(count($errors) > 0)
                                    <div class="col-12 pt-2">
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <form action="{{ route('profile-contract-download') }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary">Скачать договор</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection