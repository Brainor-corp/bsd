@if(count($files)>0)
    @foreach($files as $file)
        <div class="insert-media-element ui-state-default">
            <div class="insert-media-file-wrapper">
                <img src="{{ $file->base_url }}-200x200.{{ $file->extension }}"
                     data-insert-media-id="{{ $file->id }}"
                     data-insert-media-url="{{ $file->url }}"
                     data-insert-media-base_url="{{ $file->base_url }}"
                     data-insert-media-extension="{{ $file->extension }}"
                >
            </div>
        </div>
    @endforeach
@endif