@php
    $filterValue = null;
    if(null !== app('request')->input('filter')){
        parse_str(app('request')->input('filter'), $filterArray);
    }
    if(isset($filterArray[$name])){
        $filterValue = $filterArray[$name]['value'];
    }
@endphp
<input type="text" class="form-control filter-input"
       name="filter[{{ $name }}]"
       data-filter-name="{{ $name }}"
       data-is-like="{{ $isLike }}"
       placeholder="{{ $placeholder }}"
       @if(null !==$filterValue) value="{{ $filterValue }}" @endif
>