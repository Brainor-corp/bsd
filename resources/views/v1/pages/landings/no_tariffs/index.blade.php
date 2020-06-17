<?php
/**
 * class: LandingTemplate
 * title: Шаблон без блока с тарифами и баннера со временем/стоимостью доставки
 */
?>

@extends('v1.layouts.defaultLandingLayout')

@section('headerStyles')
    <link rel="stylesheet" href="{{ asset('v1/css/landing-default.css') }}">
@endsection

@section('content')
    @include('v1.pages.landings.no_tariffs.sections.text_1')
    @include('v1.pages.landings.no_tariffs.sections.text_2')
    @include('v1.pages.landings.no_tariffs.sections.services')
    @include('v1.pages.landings.no_tariffs.sections.about')

    <!-- Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('landing-send-mail') }}" method="post" id="calcUserForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contactModalLabel">Участвовать в акции</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="phone">Ваш телефон</label>
                            <input type="text" class="form-control" name="phone" id="phone" aria-describedby="phoneHelp" placeholder="Введите телефон" required>
                            <span class="invalid-feedback error phone" role="alert" style="display: none"></span>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="check" required>
                            <label class="form-check-label" for="check">
                                Даю согласие на обработку <a href="/politika-konfidencialnosti">персональных данных</a>.
                            </label>
                        </div>
                        <div class="form-group">
                            <div class="g-recaptcha my-3" data-sitekey="{{ env('V2_GOOGLE_CAPTCHA_KEY') }}"></div>
                            <span class="invalid-feedback error g-recaptcha-response" role="alert" style="display: none"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Отправить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footerScripts')
    <script>
        var parameters={
            max_length: 3,
            max_width: 3,
            max_height: 1.8,
            max_weight: 1000,
            max_volume: 999,
        };
    </script>

    <script src="{{ asset('v1/js/short-calculator.js') }}@include('v1.partials.versions.jsVersion')"></script>
    <script src="{{ asset('v1/js/landing-page.js') }}@include('v1.partials.versions.jsVersion')"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
