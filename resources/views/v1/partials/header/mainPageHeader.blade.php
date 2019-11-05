<header class="main-header main-page-header">
    <div class="video-inner"></div>
    <video autoplay loop muted class="bgvideo" id="bgvideo">
        <source src="video/site.mp4" type="video/mp4"></source>
    </video>
    <div class="container relative">
        @include('v1.partials.header.header-top', ['isMainPage' => true])
        @include('v1.partials.header.header-bottom')
    </div>
</header>
