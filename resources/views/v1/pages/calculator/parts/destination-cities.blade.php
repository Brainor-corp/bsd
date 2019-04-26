@if($destinationCities->count() > 0)
    @foreach($destinationCities as $destinationCity)
        <option value=""></option>
        <option value="{{ $destinationCity->id }}" data-data='{"terminal": "{{ $destinationCity->coordinates_or_address }}"}'>{{ $destinationCity->name }}</option>
    @endforeach
@endif