@extends('v1.layouts.innerPageLayout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Авторизация') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        @if(session('success'))
                            <div class="row">
                                <div class="col-12 pt-2">
                                    <div class="alert alert-success">
                                        <ul>
                                            <li>{{session('success')}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label for="login" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail или Телефон') }}</label>

                            <div class="col-md-6">
                                <input id="login" type="text" placeholder="example@domain.com или +7(XXX)XXX-XX-XX" class="form-control{{ $errors->has('email') || $errors->has('phone') ? ' is-invalid' : '' }}" name="login" value="{{ old('login') }}" required autofocus>

                                @if ($errors->has('email') || $errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') . $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Пароль') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Запомнить') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Войти') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link text-dark" href="{{ route('password.request') }}">
                                        {{ __('Забыли пароль?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
