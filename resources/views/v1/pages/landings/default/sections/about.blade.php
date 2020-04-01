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
