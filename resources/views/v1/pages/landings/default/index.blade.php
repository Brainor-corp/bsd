<?php
/**
 * class: LandingTemplate
 * title: Стандартный шаблон
 */
?>

@extends('v1.layouts.defaultLandingLayout')

@section('headerStyles')
    <link rel="stylesheet" href="{{ asset('v1/css/landing-default.css') }}">
@endsection

@section('content')
    @include('v1.pages.landings.default.sections.text_1')
    @include('v1.pages.landings.default.sections.tariffs')
    @include('v1.pages.landings.default.sections.calc_banner')
    @include('v1.pages.landings.default.sections.text_2')
    @include('v1.pages.landings.default.sections.services')
    @include('v1.pages.landings.default.sections.about')
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
