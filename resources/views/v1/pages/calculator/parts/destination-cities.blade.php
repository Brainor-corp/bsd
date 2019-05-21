@if($destinationCities->count() > 0)
    @foreach($destinationCities as $destinationCity)
        <option value=""></option>
        <option value="{{ $destinationCity->id }}" data-data='{"terminal": "{{ $destinationCity->coordinates_or_address }}","kladrId": "{{ $destinationCity->kladr->code ?? 'null' }}"}'>{{ $destinationCity->name }}</option>
    @endforeach
@endif