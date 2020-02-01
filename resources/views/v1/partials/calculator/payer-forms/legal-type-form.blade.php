<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_legal_form">Правовая форма*</label>
    <div class="col calc__inpgrp">
        <select name="payer_legal_form" id="payer_legal_form" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}>
            @foreach($counterpartyForms as $counterpartyForm)
                <option value="{{ $counterpartyForm }}"
                        @if(!empty(old('payer_legal_form')))
                            {{ old('payer_legal_form') == $counterpartyForm ? 'selected' : '' }}
                        @elseif(!empty($order->payer_legal_form))
                            {{ $order->payer_legal_form == $counterpartyForm ? 'selected' : '' }}
                        @endif
                >
                    {{ $counterpartyForm }}
                </option>
            @endforeach
        </select>
{{--        <input type="text" id="payer_legal_form" value="{{ old('payer_legal_form') ?? ($order->payer_legal_form ?? '') }}" name="payer_legal_form" class="form-control req" placeholder="ИП, ООО, АО" {{ isset($disabled) ? 'disabled' : 'required' }} />--}}
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_company_name">Название организации*</label>
    <div class="col">
        <input type="text"
               id="payer_company_name"
               name="payer_company_name"
               autocomplete="off"
               data-field="company_name"
               minlength="3"
               data-source="{{ route('getCounterparties', ['type_id' => isset($userTypes) ? $userTypes->where('slug', 'yuridicheskoe-lico')->first()->id : '']) }}"
               value="{{ old('payer_company_name') ?? ($order->payer_company_name ?? '') }}"
               class="form-control req autocomplete" {{ isset($disabled) ? 'disabled' : 'required' }}
        />
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Юридический адрес*</label>
    <div class="col">
        <input type="text" name="payer_legal_address_city" value="{{ old('payer_legal_address_city') ?? ($order->payer_legal_address_city ?? '') }}" class="form-control form-item req"  placeholder="Город" {{ isset($disabled) ? 'disabled' : 'required' }}/>
        <input type="text" name="payer_legal_address" value="{{ old('payer_legal_address') ?? ($order->payer_legal_address ?? '') }}" class="form-control form-item req"  placeholder="Адрес" {{ isset($disabled) ? 'disabled' : 'required' }}/>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_inn">ИНН*</label>
    <div class="col calc__inpgrp"><input type="text" maxlength="12" id="payer_inn" name="payer_inn" value="{{ old('payer_inn') ?? ($order->payer_inn ?? '') }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_kpp">КПП*</label>
    <div class="col calc__inpgrp"><input type="text" maxlength="9" id="payer_kpp" name="payer_kpp" value="{{ old('payer_kpp') ?? ($order->payer_kpp ?? '') }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_contact_person_legal">Контактное лицо*</label>
    <div class="col calc__inpgrp"><input type="text" id="payer_contact_person_legal" class="form-control req" name="payer_contact_person_legal" value="{{ old('payer_contact_person_legal') ?? ($order->payer_contact_person ?? '') }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_phone_legal">Телефон*</label>
    <div class="col calc__inpgrp"><input type="text" placeholder="+7(XXX)XXX-XX-XX" class="form-control req phone-mask" id="payer_phone_legal" name="payer_phone_legal" value="{{ old('payer_phone_legal') ?? ($order->payer_phone ?? '') }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_addition_info_legal">Дополнительная информация</label>
    <div class="col"><input type="text" id="payer_addition_info_legal" name="payer_addition_info_legal" value="{{ old('payer_addition_info_legal') ?? ($order->payer_addition_info ?? '') }}" class="form-control" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>
