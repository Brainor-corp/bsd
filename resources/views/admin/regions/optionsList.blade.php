<option value="">Не указан</option>
@foreach($thresholds as $threshold)
    <option value="{{ $threshold->id }}">{{ $threshold->value }}</option>
@endforeach