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
