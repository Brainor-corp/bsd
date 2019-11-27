@extends('v1.layouts.mainPageLayout')

@section('headerStyles')
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
@endsection
@section('content')
<section class="service">
    <div class="container">
        <div class="title-inline d-flex align-items-center">
            <div class="margin-item"><h3>Услуги</h3></div>
            <a href="{{ url('/uslugi') }}" class="link-with-dotted margin-item">Все услуги</a>
        </div>
        @if(count($servicesPostsChunk)>0)
            @foreach($servicesPostsChunk as $servicesPosts)
                <div class="row row-item justify-content-md-center">
                    @foreach($servicesPosts as $service)
                        <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                            <a href="{{ $service->url }}">
                                <div class="service__block d-flex relative {{$service->slug}}">
                                    <div class="service__block_body">
                                        <div class="service__block_title">{{$service->title}}</div>
                                        <p class="service__block_disc">{{$service->description}}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
</section>
<section class="about-company bg-dark mb-0">
    <div class="container">
        @if($aboutPage)
            {!! $aboutPage->content !!}
        @endif
    </div>
</section>
<section class="custom-text bg-white mb-0">
    <div class="container">
        @if($textBlock)
            {!! $textBlock->content !!}
        @endif
    </div>
</section>
@endsection
