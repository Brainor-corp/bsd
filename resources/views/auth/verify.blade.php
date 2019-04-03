@extends('v1.layouts.innerPageLayout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Подтвердите Ваш Email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Ссылка с подтверждением отправлена на Ваш Email') }}
                        </div>
                    @endif

                    {{ __('Чтобы продолжить, перейдите по ссылке из письма, которое мы отправили на Ваш Email.') }}
                    {{ __('Если Вы не получили Email') }}, <a href="{{ route('verification.resend') }}">{{ __('нажмите сюда для повторной отправки') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
