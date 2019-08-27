@if($destinationCities->count() > 0)
    @foreach($destinationCities as $destinationCity)
        <option value=""></option>
        <option value="{{ $destinationCity->id }}">{{ $destinationCity->name }}</option>
    @endforeach
@endif