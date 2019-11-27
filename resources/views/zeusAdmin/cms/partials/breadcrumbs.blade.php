<div class="breadcrumb__list d-flex">
    <div class="container">
        <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
        @foreach($ancestors as $ancestor)
            @if($loop->last)
                <span class="breadcrumb__item">{{ $ancestor->title }}</span>
            @else
                <span class="breadcrumb__item"><a href="{{ url($ancestor->url) }}" class="">{{ $ancestor->title }}</a></span>
            @endif

        @endforeach
    </div>
</div>