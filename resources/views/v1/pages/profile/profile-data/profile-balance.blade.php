@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="##" class="">Личный кабинет</a></span>
            <span class="breadcrumb__item">Баланс</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @include('v1.partials.profile.profile-tabs')
                    <header class="wrapper__header">
                        <h1>Баланс</h1>
                    </header>
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12 pt-2">
                                    <div class="alert alert-danger d-none">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-inline">
                                <input type="text" readonly class="form-control mr-3" id="balance-input">
                                <span id="update-balance-btn">
                                    <i class="fa update fa-2x fa-undo"></i>
                                    <img class="d-none loading-svg align-bottom" src="{{ asset('images/loading.svg') }}" alt="">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footScripts')
    <script src="{{ asset('v1/js/profile-balance.js') }}"></script>
@endsection