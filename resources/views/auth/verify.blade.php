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
                    {{ __('Если Вы не получили Email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf

                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                            {{ __('нажмите сюда для повторной отправки') }}
                        </button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
