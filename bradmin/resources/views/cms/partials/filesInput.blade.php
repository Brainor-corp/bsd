@php
    $field = array();
    $field['name'] =  pathinfo($file->url)['basename'];
    $field['url'] =  $file->url;
    switch (pathinfo($file->url)['extension']) {
        case 'jpg':
        case 'png':
        case 'svg':
        case 'gif': $field['image'] = $file->url; break;
        case 'txt': $field['image'] = '/bradmin/images/txt.png'; break;
        case 'doc':
        case 'docx': $field['image'] = '/bradmin/images/doc.png'; break;
        case 'xlsx':
        case 'xml': $field['image'] = '/bradmin/images/excel.png'; break;
        default: $field['image'] = '/bradmin/images/file.png'; break;
    }
@endphp
<div class="tiles-display br-display">
    <div class="card w-100">
        <div class="card-img-top text-center pt-3" style="background-image: url({{ $field['image'] ?? null }})">
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <td>Id</td>
                    <td>{{ $file->id }}</td>
                </tr>
                <tr>
                    <td>Тип</td>
                    <td>{{ $file->mime }}</td>
                </tr>
                <tr>
                    <td>Размер</td>
                    <td>{{ $file->size }} байт ({{ round($file->size / 1024, 2) }} кб)</td>
                </tr>
                <tr>
                    <td>Путь</td>
                    <td>{{ $file->path }}</td>
                </tr>
                <tr>
                    <td>Создан</td>
                    <td>{{ $file->created_at }}</td>
                </tr>
            </table>
        </div>
        <div class="card-footer text-center">
            @if(isset($field['name']))
                <a href="{{ url($field['url']) }}" target="_blank" class="px-1"><i class="fas fa-link"></i> {{ $field['name'] }}</a>
            @endif
        </div>
        {{--<img class="card-img-top img-fluid" src="{{ $file->url ?? null }}" alt="">--}}
    </div>
</div>