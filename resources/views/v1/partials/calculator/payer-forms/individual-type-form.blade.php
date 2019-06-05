<div class="form-item row align-items-center">
    <label class="col-auto calc__label">ФИО*</label>
    <div class="col"><input type="text" class="form-control req" name="payer_name_individual" value="{{ $order->payer_name ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }} /></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Паспорт*</label>
    <div class="col calc__inpgrp">
        <div class="input-group">
            <input type="text" class="form-control text-center form-item req" name="payer_passport_series" value="{{ $order->payer_passport_series ?? '' }}" placeholder="Серия" {{ isset($disabled) ? 'disabled' : 'required' }} />
            <input type="text" class="form-control text-center form-item req" name="payer_passport_number" value="{{ $order->payer_passport_number ?? '' }}" placeholder="Номер" {{ isset($disabled) ? 'disabled' : 'required' }} />
        </div>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_contact_person_individual">Контактное лицо*</label>
    <div class="col calc__inpgrp"><input type="text" class="form-control req" id="payer_contact_person_individual" name="payer_contact_person_individual" value="{{ $order->payer_contact_person ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_phone_individual">Телефон*</label>
    <div class="col calc__inpgrp"><input type="text" class="form-control req" name="payer_phone_individual" id="payer_phone_individual" value="{{ $order->payer_phone ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_addition_info_individual">Дополнительная информация</label>
    <div class="col"><input type="text" id="payer_addition_info_individual" name="payer_addition_info_individual" value="{{ $order->payer_addition_info ?? '' }}" class="form-control" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>