@if(isset($landingPage))
    <div class="alert alert-warning mb-3">
        Чтобы изменённые данные отразились на странице, необходимо
        <a href="{{ route('cache-clear', ['key' => $landingPage->url]) }}" class="btn btn-sm btn-danger">
            очистить кеш страницы
        </a>
    </div>
@endif
