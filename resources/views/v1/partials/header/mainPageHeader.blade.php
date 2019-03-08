<header class="main-header main-page-header">
    <video autoplay loop muted class="bgvideo" id="bgvideo">
        <source src="/video/PexelsVideos3566.mp4" type="video/mp4"></source>
    </video>
    <div class="container relative">
        @include('v1.partials.header.header-top', ['isMainPage' => true])
        @include('v1.partials.header.header-bottom')
    </div>
</header>