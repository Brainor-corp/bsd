<div class="form-item row align-items-center">
    <label class="col-auto calc__label">ФИО*</label>
    <div class="col"><input type="text" class="form-control req" name="sender_name" value="{{ $order->sender_name ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }} /></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Паспорт*</label>
    <div class="col calc__inpgrp">
        <div class="input-group">
            <input type="text" class="form-control text-center form-item req" name="sender_passport_series" value="{{ $order->sender_passport_series ?? '' }}" placeholder="Серия" {{ isset($disabled) ? 'disabled' : 'required' }} />
            <input type="text" class="form-control text-center form-item req" name="sender_passport_number" value="{{ $order->sender_passport_number ?? '' }}" placeholder="Номер" {{ isset($disabled) ? 'disabled' : 'required' }} />
        </div>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Контактное лицо*</label>
    <div class="col calc__inpgrp"><input type="text" class="form-control req" name="sender_name" value="{{ $order->sender_name ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_phone">Телефон*</label>
    <div class="col calc__inpgrp"><input type="text" class="form-control req" name="sender_phone" id="sender_phone" value="{{ $order->sender_phone ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_addition_info">Дополнительная информация</label>
    <div class="col"><input type="text" id="sender_addition_info" name="sender_addition_info" value="{{ $order->sender_addition_info ?? '' }}" class="form-control" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>