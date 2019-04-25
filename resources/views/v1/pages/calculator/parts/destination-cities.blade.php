@if($destinationCities->count() > 0)
    @foreach($destinationCities as $destinationCity)
        <option value=""></option>
        <option value="{{ $destinationCity->id }}" data-data='{"terminal": "{{ isset($destinationCity->terminal) ? addcslashes(($destinationCity->terminal->revert_geo_point ?? $destinationCity->terminal->address), '"') : $destinationCity->name }}"}'>{{ $destinationCity->name }}</option>
    @endforeach
@endif