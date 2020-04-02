@if(isset($landingPage))
    <div class="alert alert-warning mb-3">
        Чтобы изменённые данные отразились на странице, необходимо
        <a href="{{ route('landing-cache-clear', ['url' => "$landingPage->url"]) }}" class="btn btn-sm btn-danger">
            очистить кеш страницы
        </a>
    </div>
@endif
