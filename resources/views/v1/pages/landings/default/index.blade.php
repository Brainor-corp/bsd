<?php
/**
 * class: LandingTemplate
 * title: Стандартный шаблон
 */
?>

@extends('v1.layouts.defaultLandingLayout')

@section('content')
    <section class="mt-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @include('v1.pages.landings.default.sections.text_1')
                </div>
            </div>
        </div>
    </section>

    @include('v1.pages.landings.default.sections.tariffs')
    @include('v1.pages.landings.default.sections.calc_banner')
    @include('v1.pages.landings.default.sections.text_2')
    @include('v1.pages.landings.default.sections.services')
    @include('v1.pages.landings.default.sections.about')
@endsection
