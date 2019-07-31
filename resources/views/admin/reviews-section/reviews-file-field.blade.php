<div class="form-group">
    @if(isset($file))
        Файл отзыва: <a href="{{ url($file->base_url) }}" target="_blank">{{ $file->name }}</a>
        <hr>
    @endif
    <input type="file" name="review-file">
</div>