<div class="insert-media-wrapper">
<div class="files-list row selectable" id="selectable">
    @if(count($files)>0)
        @foreach($files as $file)
            @if(strstr($file->mime, "image/"))
                @php
                    $thumb_url = $file->base_url;
                    $extension = $file->extension;
                @endphp
            @else
                @php
                    $extension = 'png';
                    $ext_url = public_path().'/uploads/'.$file->extension.'-200x200.'.$extension;
                @endphp
                @if(file_exists($ext_url))
                    @php
                        $thumb_url = '/uploads/'.$file->extension;
                    @endphp
                @else
                    @php
                        $thumb_url = '/uploads/doc';
                    @endphp
                @endif
            @endif
            <div class="insert-media-element ui-state-default">
                <div class="insert-media-file-wrapper">
                    <img src="{{ $thumb_url }}-200x200.{{ $extension }}"
                         data-insert-media-id="{{ $file->id }}"
                         data-insert-media-url="{{ $file->url }}"
                         data-insert-media-base_url="{{ $file->base_url }}"
                         data-insert-media-base_name="{{ basename($file->url) }}"
                         data-insert-media-extension="{{ $file->extension }}"
                         data-insert-media-title="{{ $file->title }}"
                         data-insert-media-alt="{{ $file->alt }}"
                         title="{{ basename($file->url) }}"
                    >
                </div>
            </div>
        @endforeach
        <div id="image-list-more-last-{{ $wrapperId }}"></div>
    @else
        <div class="alert alert-warning" role="alert">
            <strong>Внимание!</strong> Выбранных файлов нет.
        </div>
    @endif
</div>
</div>
<script>
    $( function() {
        $( ".selectable" ).selectable();
    } );
</script>
<button type="button" class="btn btn-default image-list-more" id="image-list-more" data-wrapper-key="{{ $wrapperId }}" data-file-type="image" data-request-count="{{ $requestCount }}">Eщё файлы</button>
