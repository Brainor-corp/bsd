<label for="url">Адрес страницы (url, ЧПУ) ("." для автогенерации) <span class="text-danger">*</span></label>
<div class="input-group mb-3">
    <div class="input-group-prepend">
        <span class="input-group-text">{{ url('/promo') }}/</span>
    </div>
    <input type="text" class="form-control" id="url" name="url" value="{{ $landingPage->url ?? '.' }}" required>
    @if(!empty($landingPage))
        <a href="{{ route('landing-index', ['url' => $landingPage->url]) }}" target="_blank" class="btn btn-secondary ml-2">Просмотреть</a>
    @endif
</div>
