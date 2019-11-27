<div class="form-group">
    <div id="loadedFile">
        @if(isset($file))
            Файл отзыва: <a href="{{ url($file->base_url) }}" target="_blank">{{ $file->name }}</a>
            <button class="btn btn-danger py-0" onclick="$('#loadedFile').remove()">Удалить</button>
            <hr>
            <input type="hidden" name="current-file-exists" value="1">
        @endif
    </div>

    <div>
        <input type="file" name="review-file">
    </div>
</div>
