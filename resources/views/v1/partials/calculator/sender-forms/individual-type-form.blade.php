<div class="form-item row align-items-center">
    <label class="col-auto calc__label">ФИО*</label>
    <div class="col">
        <input type="text"
               class="form-control req autocomplete"
               data-field="name"
               data-source="{{ route('getCounterparties', ['type_id' => isset($userTypes) ? $userTypes->where('slug', 'fizicheskoe-lico')->first->id : '']) }}"
               autocomplete="off"
               name="sender_name_individual"
               minlength="3"
               maxlength="50"
               value="{{ old('sender_name_individual') ?? ($order->sender_name ?? '') }}"
               {{ isset($disabled) ? 'disabled' : 'required' }}
        />
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Паспорт*</label>
    <div class="col calc__inpgrp">
        <div class="input-group">
            <input type="text" pattern="\d{4}" maxlength="4" class="form-control text-center form-item req" name="sender_passport_series" value="{{ old('sender_passport_series') ?? ($order->sender_passport_series ?? '') }}" placeholder="Серия" {{ isset($disabled) ? 'disabled' : 'required' }} />
            <input type="text" pattern="\d{6}" maxlength="6" class="form-control text-center form-item req" name="sender_passport_number" value="{{ old('sender_passport_number') ?? ($order->sender_passport_number ?? '') }}" placeholder="Номер" {{ isset($disabled) ? 'disabled' : 'required' }} />
        </div>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_contact_person_individual">Контактное лицо</label>
    <div class="col calc__inpgrp"><input type="text" class="form-control" name="sender_contact_person_individual" id="sender_contact_person_individual" value="{{ old('sender_contact_person_individual') ?? ($order->sender_contact_person ?? '') }}" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_phone_individual">Телефон*</label>
    <div class="col calc__inpgrp"><input type="text" placeholder="+7(XXX)XXX-XX-XX" class="form-control req phone-mask" name="sender_phone_individual" id="sender_phone_individual" value="{{ old('sender_phone_individual') ?? ($order->sender_phone ?? '') }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_addition_info_individual">Дополнительная информация</label>
    <div class="col"><input type="text" maxlength="500" id="sender_addition_info_individual" name="sender_addition_info_individual" value="{{ old('sender_addition_info_individual') ?? ($order->sender_addition_info ?? '') }}" class="form-control" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>
