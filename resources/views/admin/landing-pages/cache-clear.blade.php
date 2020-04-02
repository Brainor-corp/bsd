@if(isset($landingPage))
    <div class="input-group mb-3">
        <a href="{{ route('cache-clear', ['key' => $landingPage->url]) }}" class="btn btn-danger">Очистить кеш страницы</a>
    </div>
@endif
