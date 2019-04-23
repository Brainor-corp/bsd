@php
        $filterValue = null;
        if(null !== app('request')->input('filter')){
            parse_str(app('request')->input('filter'), $filterArray);
        }
        if(isset($filterArray[$name])){
            $filterValue = $filterArray[$name]['value'];
        }
@endphp
<select name="filter[{{ $name }}]" data-filter-name="{{ $name }}" data-is-like="{{ $isLike }}" class="form-control filter-input">
        <option value="" selected disabled>Выберите пункт</option>
    @foreach($options as $key => $option)
        <option value="{{ $key }}" @if($filterValue == $key) selected @endif>{{ $option }}</option>
    @endforeach
</select>