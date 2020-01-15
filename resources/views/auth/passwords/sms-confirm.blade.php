@extends('v1.layouts.innerPageLayout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Подтверждение по СМС') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.restore-phone-confirm-action') }}" id="confirmCodeForm">
                        @csrf
                        <input type="hidden" name="q" value="{{ $encryptedPhone }}">

                        @if($errors->has('error'))
                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="alert alert-danger">
                                        <strong>{{ $errors->first('error') }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($errors->has('g-recaptcha-response'))
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-danger">
                                        Подтвердите, что Вы не робот.
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <p>
                                        На номер <strong>+{{ substr($phone, 0, 1) }} (***) -***-{{ substr($phone, -4) }}</strong> отправлено СМС с <strong>кодом</strong> подтверждения.
                                    </p>
                                    <p>
                                        Пожалуйста, введите полученный <strong>код</strong> и нажмите "Подтвердить".
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code" class="col-md-4 py-0 col-form-label text-md-right">
                                {{ __('Код подтверждения') }}
                                <br>
                                <a href="{{ route('password.resend-sms-code', ['q' => $encryptedPhone]) }}" class="mt-2">
                                    {{ __('Получить код повторно') }}
                                </a>
                            </label>

                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ old('code') }}" required>
                                @if ($errors->has('code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="g-recaptcha" data-sitekey="{{ env('V2_GOOGLE_CAPTCHA_KEY') }}"></div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Подтвердить') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footScripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
