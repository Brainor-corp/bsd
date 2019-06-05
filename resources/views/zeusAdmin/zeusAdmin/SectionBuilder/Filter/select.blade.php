<select name="filter[{{ $name }}]" data-filter-name="{{ $name }}" class="form-control filter-input">
        <option value="" selected disabled>Выберите пункт</option>
    @foreach($options as $key => $option)
        <option value="{{ $key }}">{{ $option }}</option>
    @endforeach
</select>