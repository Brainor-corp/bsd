<div class="form-group date">
    <label for="input_{{ $name }}">{{ $label }} @if($required) <span class="text-danger">*</span> @endif </label>
    <input id="input_{{ $name }}"
           name="{{ $name }}"
           type="text"
           class="form-control datepicker"
           value="{{ $value }}"
           autocomplete="off"
           data-datepicker-format="{{ $format }}"
           data-datepicker-language="{{ $language }}"
           data-datepicker-todayBtn="{{ $todayBtn }}"
           data-datepicker-clearBtn="{{ $clearBtn }}"
           data-datepicker-minuteStep="{{ $minuteStep }}"
           @if($readonly) disabled @endif
           @if($required) required @endif>
</div>