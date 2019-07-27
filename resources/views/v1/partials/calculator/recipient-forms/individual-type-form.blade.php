<div class="form-item row align-items-center">
    <label class="col-auto calc__label">ФИО*</label>
    <div class="col">
        <input type="text"
               class="form-control req autocomplete"
               name="recipient_name_individual"
               autocomplete="off"
               data-field="name"
               data-source="{{ route('getCounterparties', ['type_id' => isset($userTypes) ? $userTypes->where('slug', 'fizicheskoe-lico')->first->id : '']) }}"
               value="{{ old('recipient_name_individual') ?? ($order->recipient_name ?? '') }}"
               {{ isset($disabled) ? 'disabled' : 'required' }}
        />
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Паспорт*</label>
    <div class="col calc__inpgrp">
        <div class="input-group">
            <input type="text" pattern="\d{4}" maxlength="4" class="form-control text-center form-item req" name="recipient_passport_series" value="{{ old('recipient_passport_series') ?? ($order->recipient_passport_series ?? '') }}" placeholder="Серия" {{ isset($disabled) ? 'disabled' : 'required' }} />
            <input type="text" pattern="\d{6}" maxlength="6" class="form-control text-center form-item req" name="recipient_passport_number" value="{{ old('recipient_passport_number') ?? ($order->recipient_passport_number ?? '') }}" placeholder="Номер" {{ isset($disabled) ? 'disabled' : 'required' }} />
        </div>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_contact_person_individual">Контактное лицо*</label>
    <div class="col calc__inpgrp"><input type="text" class="form-control req" name="recipient_contact_person_individual" id="recipient_contact_person_individual" value="{{ old('recipient_contact_person_individual') ?? ($order->recipient_contact_person ?? '') }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_phone_individual">Телефон*</label>
    <div class="col calc__inpgrp"><input type="text" placeholder="+7(XXX)XXX-XX-XX" class="form-control req phone-mask" name="recipient_phone_individual" id="recipient_phone_individual" value="{{ old('recipient_phone_individual') ?? ($order->recipient_phone ?? '') }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_addition_info_individual">Дополнительная информация</label>
    <div class="col"><input type="text" id="recipient_addition_info_individual" name="recipient_addition_info_individual" value="{{ old('recipient_addition_info_individual') ?? ($order->recipient_addition_info ?? '') }}" class="form-control" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>