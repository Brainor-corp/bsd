@if($destinationCities->count() > 0)
    @foreach($destinationCities as $destinationCity)
        <option value=""></option>
        <option value="{{ $destinationCity->name }}"
                data-data='{
                    "terminal": "{{ $destinationCity->coordinates_or_address }}",
                    "kladrId": "{{ $destinationCity->kladr->code ?? 'null' }}",
                    "doorstep": "{{ $destinationCity->doorstep }}",
                    "doorstep_message": "{{ addcslashes($destinationCity->doorstep_message->name ?? '', '"') }}"
                }'
        >
            {{ $destinationCity->name }}
        </option>
    @endforeach
@endif
