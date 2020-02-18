<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_legal_form">Правовая форма*</label>
    <div class="col calc__inpgrp">
{{--        <input type="text"--}}
{{--               id="sender_legal_form"--}}
{{--               value="{{ old('sender_legal_form') ?? ($order->sender_legal_form ?? '') }}"--}}
{{--               name="sender_legal_form"--}}
{{--               class="form-control req"--}}
{{--               placeholder="ИП, ООО, АО"--}}
{{--               {{ isset($disabled) ? 'disabled' : 'required' }}--}}
{{--        />--}}
        <select name="sender_legal_form" id="sender_legal_form" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}>
            @foreach($counterpartyForms as $counterpartyForm)
                <option value="{{ $counterpartyForm }}"
                @if(!empty(old('sender_legal_form')))
                    {{ old('sender_legal_form') == $counterpartyForm ? 'selected' : '' }}
                    @elseif(!empty($order->sender_legal_form))
                    {{ $order->sender_legal_form == $counterpartyForm ? 'selected' : '' }}
                    @endif
                >
                    {{ $counterpartyForm }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_company_name">Название организации*</label>
    <div class="col">
        <input type="text"
               id="sender_company_name"
               name="sender_company_name"
               autocomplete="off"
               data-field="company_name"
               minlength="3"
               maxlength="50"
               data-source="{{ route('getCounterparties', ['type_id' => isset($userTypes) ? $userTypes->where('slug', 'yuridicheskoe-lico')->first()->id : '']) }}"
               value="{{ old('sender_company_name') ?? ($order->sender_company_name ?? '') }}"
               class="form-control req autocomplete"
                {{ isset($disabled) ? 'disabled' : 'required' }}
        />
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Юридический адрес*</label>
    <div class="col">
        <input type="text" name="sender_legal_address_city" maxlength="70" value="{{ old('sender_legal_address_city') ?? ($order->sender_legal_address_city ?? '') }}" class="form-control form-item req"  placeholder="Город" {{ isset($disabled) ? 'disabled' : 'required' }}/>
        <input type="text" name="sender_legal_address" maxlength="190" value="{{ old('sender_legal_address') ?? ($order->sender_legal_address ?? '') }}" class="form-control form-item req"  placeholder="Адрес" {{ isset($disabled) ? 'disabled' : 'required' }}/>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_inn">ИНН*</label>
    <div class="col calc__inpgrp"><input type="text" maxlength="12" id="sender_inn" name="sender_inn" value="{{ old('sender_inn') ?? ($order->sender_inn ?? '') }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_kpp">КПП*</label>
    <div class="col calc__inpgrp"><input type="text" maxlength="9" id="sender_kpp" name="sender_kpp" value="{{ old('sender_kpp') ?? ($order->sender_kpp ?? '') }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_contact_person_legal">Контактное лицо*</label>
    <div class="col calc__inpgrp"><input type="text" id="sender_contact_person_legal" class="form-control req" name="sender_contact_person_legal" value="{{ old('sender_contact_person_legal') ?? ($order->sender_contact_person ?? '') }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_phone_legal">Телефон*</label>
    <div class="col calc__inpgrp"><input type="text" placeholder="+7(XXX)XXX-XX-XX" class="form-control req phone-mask" id="sender_phone_legal" name="sender_phone_legal" value="{{ old('sender_phone_legal') ?? ($order->sender_phone ?? '') }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_addition_info_legal">Дополнительная информация</label>
    <div class="col"><input type="text" maxlength="500" id="sender_addition_info_legal" name="sender_addition_info_legal" value="{{ old('sender_addition_info_legal') ?? ($order->sender_addition_info ?? '') }}" class="form-control" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>
