<div class="row pb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row w-100 align-items-center">
                    <div class="col-auto">
                        @if($firedSection->isCreatable())
                            <a @click.prevent="$emit('redirectTo',$event)" href="{{ Request::url() }}/create" class="btn btn-primary">Создать</a>
                        @endif
                    </div>
                    <div class="col">
                        {!! $nav !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="tiles-display br-display" data-delete-redirect="{{ $pluginData['redirectUrl'] ?? null }}" data-section-path="{{ $pluginData['sectionPath'] ?? null }}">
            <div class="row">
                @foreach($fields as $field)
                    <div class="col-lg-2 col-sm-6 col-12 mb-3">
                        <div class="card tile">
                            @php
                                if(isset($field['image'])) {
                                    $field['name'] =  pathinfo($field['image'])['basename'];
                                    $field['url'] =  $field['image'];
                                    switch (pathinfo($field['image'])['extension']) {
                                        case 'jpg':
                                        case 'png':
                                        case 'svg':
                                        case 'gif': break;
                                        case 'txt': $field['image'] = '/zeusAdmin/images/txt.png'; break;
                                        case 'doc':
                                        case 'docx': $field['image'] = '/zeusAdmin/images/doc.png'; break;
                                        case 'xlsx':
                                        case 'xml': $field['image'] = '/zeusAdmin/images/excel.png'; break;
                                        default: $field['image'] = '/zeusAdmin/images/file.png'; break;
                                    }
                                }
                            @endphp
                            <div class="card-img-top text-center pt-3" style="background-image: url({{ $field['image'] ?? null }})">
                                @if(isset($field['name']))
                                    <a href="{{ url($field['url']) }}" target="_blank" class="px-1"><i class="fas fa-link"></i> {{ $field['name'] }}</a>
                                @endif
                            </div>
                            <div class="card-body pb-0 pt-0 px-0">
                                <table class="table table-responsive mb-0">
                                    @foreach($elements as $element)
                                        @if($element->isHeaderImage()[1])
                                            <tr>
                                                <th scope="col">{{ $element->getLabel() }}</th>
                                                <td scope="col">
                                                    @if(!$field[$element->getName()] instanceof Countable)
                                                        {!! $field[$element->getName()] !!}
                                                    @else
                                                        @php
                                                            $path = explode('.', $element->getName());
                                                            $name = end($path);
                                                        @endphp
                                                        @foreach($field[$element->getName()] as $value)
                                                            <span class="badge badge-info text-white">{!! $value->{$name} !!}</span>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </div>
                            <div class="card-footer">
                                @if($firedSection->isEditable())
                                    <a @click.prevent="$emit('redirectTo',$event)" href="{{ parse_url(Request::url(), PHP_URL_PATH) . '/' . $field['brRowId'] . '/edit' }}" class="text-success">Ред.</a>
                                @endif
                                @if($firedSection->isDeletable())
                                    <button @click="$emit('showDeleteModal',$event)" type="button" class="delete-btn text-danger bg-transparent border-0 float-right" data-delete-link="{{ Request::url() . '/' . $field['brRowId'] . '/delete' }}">Удал.</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{--<div class="table-responsive tiles-table br-display" data-delete-redirect="{{ $pluginData['redirectUrl'] ?? null }}" data-section-path="{{ $pluginData['sectionPath'] ?? null }}">--}}
    {{--<table class="table">--}}
        {{--<thead>--}}
        {{--<tr>--}}
            {{--@foreach($elements as $element)--}}
                {{--<th scope="col">{{ $element->getLabel() }}</th>--}}
            {{--@endforeach--}}
            {{--<th></th>--}}
        {{--</tr>--}}
        {{--</thead>--}}
        {{--<tbody>--}}
        {{--@foreach($fields as $field)--}}
            {{--<tr>--}}
                {{--@foreach($elements as $element)--}}
                    {{--<td scope="col">--}}
                        {{--@if(!$field[$element->getName()] instanceof Countable)--}}
                            {{--{!! $field[$element->getName()] !!}--}}
                        {{--@else--}}
                            {{--@php--}}
                                {{--$path = explode('.', $element->getName());--}}
                                {{--$name = end($path);--}}
                            {{--@endphp--}}
                            {{--@foreach($field[$element->getName()] as $value)--}}
                                {{--<span class="badge badge-info text-white">{!! $value->{$name} !!}</span>--}}
                            {{--@endforeach--}}
                        {{--@endif--}}
                    {{--</td>--}}
                {{--@endforeach--}}
                {{--<td class="text-right">--}}
                    {{--@if($firedSection->isEditable())--}}
                        {{--<a @click.prevent="$emit('redirectTo',$event)" href="{{ parse_url(Request::url(), PHP_URL_PATH) . '/' . $field['brRowId'] . '/edit' }}" class="text-success">Ред.</a>--}}
                    {{--@endif--}}
                    {{--@if($firedSection->isDeletable())--}}
                        {{--<button @click="$emit('showDeleteModal',$event)" type="button" class="delete-btn text-danger bg-transparent border-0" data-delete-link="{{ Request::url() . '/' . $field['brRowId'] . '/delete' }}">Удал.</button>--}}
                    {{--@endif--}}
                {{--</td>--}}
            {{--</tr>--}}
        {{--@endforeach--}}
        {{--</tbody>--}}
    {{--</table>--}}
{{--</div>--}}
