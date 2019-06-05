<div class="form-group">
    <label for="input_{{ $name }}">{{ $label }} @if($required) <span class="text-danger">*</span> @endif</label>
    <textarea class="form-control wysiwyg_editor"
              id="input_{{ $name }}"
              name="{{ $name }}"
              cols="{{ $cols }}"
              rows="{{ $rows }}"
              @if($required) required @endif
              @if($readonly) readonly @endif
              placeholder="{{ $placeholder ?? null }}">{!! htmlspecialchars($value) ?? null !!}</textarea>
</div>