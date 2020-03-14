@extends('v1.layouts.innerPageLayout')

@section('headerStyles')
    <link rel="stylesheet" href="{{ asset('packages/jquery-ui/jquery-ui.css') }}">
@endsection

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="{{ url('/klientam') }}" class="">Клиентам</a></span>
            <span class="breadcrumb__item">Статус груза</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Статус груза</h1>
                    </header>
                    <form action="{{ route('shipment-search-wrapper') }}" method="post">
                        @csrf
                        <div class="status-shipment__header">
                            <div class="reports__header row align-items-center">
                                <div class="col-12">
                                    <span class="reports__header-label">Поиск:</span>
                                    <div id="search-wrapper" class="d-flex flex-wrap control-group">
                                        @php($currentType = old('type') ?? 'id')
                                        <select name="type" class="custom-select">
                                            <option @if($currentType === 'id') selected @endif value="id">По номеру заявки</option>
                                            <option @if($currentType === 'cargo_number') selected @endif value="cargo_number">По номеру ЭР</option>
                                        </select>
                                        <input name="query"
                                               type="text"
                                               class="form-control search-input mr-3 autocomplete"
                                               placeholder="{{ $currentType === 'id' ? 'Введите номер (напр.: 123)' : 'Введите номер (напр.: СП-00000)' }}"
                                               value="{{ old('query') }}"
                                               data-source="{{ route('get-cargo-numbers') }}"
                                               maxlength="100"
                                               required
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="g-recaptcha mb-3" data-sitekey="{{ env('V2_GOOGLE_CAPTCHA_KEY') }}"></div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        <div class="mb-3">
                                            <span class="invalid-feedback" role="alert" style="display: block">
                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-danger">Найти груз</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footScripts')
    <script src="{{ asset('packages/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('v1/js/status-page.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
