@extends('v1.layouts.innerPageLayout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Восстановление пароля') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.method-redirect') }}" id="sendRestoreMethod">
                        @csrf

                        @if($errors->has('error'))
                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="alert alert-danger">
                                        <strong>{{ $errors->first('error') }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($errors->has('gToken'))
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-danger">
                                        {{ $errors->first('gToken') }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('или') }}</label>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Телефон') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }} phone-mask" name="phone" value="{{ old('phone') }}">

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Восстановить') }}
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
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ env('GOOGLE_CAPTCHA_KEY') }}', {action: 'feedbackForm'}).then(function(token) {
                $('#sendRestoreMethod').append('<input type="hidden" name="gToken" value="'+token+'">')
            });
        });
    </script>
@endsection
