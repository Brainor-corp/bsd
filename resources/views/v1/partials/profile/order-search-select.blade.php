<select id="search-select" class="custom-select">
    <option disabled="" value="" selected="">Выберите тип заказа</option>
    @foreach($types as $type)
        <option value="{{ $type->id }}">{{ $type->name }}</option>
    @endforeach
</select>