@if(isset($file))
    <div class="form-group">
        Файлы отзыва: <a href="{{ url($file->base_url) }}">{{ $file->name }}</a>
    </div>
@endif