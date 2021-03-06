<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_legal_form">Правовая форма*</label>
    <div class="col calc__inpgrp">
{{--        <input type="text"--}}
{{--               id="recipient_legal_form"--}}
{{--               value="{{ old('recipient_legal_form') ?? ($order->recipient_legal_form ?? '') }}"--}}
{{--               name="recipient_legal_form"--}}
{{--               class="form-control req"--}}
{{--               placeholder="ИП, ООО, АО" {{ isset($disabled) ? 'disabled' : 'required' }}--}}
{{--        />--}}
        <select name="recipient_legal_form" id="recipient_legal_form" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}>
            @foreach($counterpartyForms as $counterpartyForm)
                <option value="{{ $counterpartyForm }}"
                @if(!empty(old('recipient_legal_form')))
                    {{ old('recipient_legal_form') == $counterpartyForm ? 'selected' : '' }}
                    @elseif(!empty($order->recipient_legal_form))
                    {{ $order->recipient_legal_form == $counterpartyForm ? 'selected' : '' }}
                    @endif
                >
                    {{ $counterpartyForm }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_company_name">Название организации*</label>
    <div class="col">
        <input type="text"
               id="recipient_company_name"
               name="recipient_company_name"
               autocomplete="off"
               data-field="company_name"
               minlength="3"
               maxlength="50"
               data-source="{{ route('getCounterparties', ['type_id' => isset($userTypes) ? $userTypes->where('slug', 'yuridicheskoe-lico')->first()->id : '']) }}"
               value="{{ old('recipient_company_name') ?? ($order->recipient_company_name ?? '') }}"
               class="form-control req autocomplete"
               {{ isset($disabled) ? 'disabled' : 'required' }}
        />
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Юридический адрес*</label>
    <div class="col">
        <input type="text" name="recipient_legal_address_city" maxlength="70" value="{{ old('recipient_legal_address_city') ?? ($order->recipient_legal_address_city ?? '') }}" class="form-control form-item req"  placeholder="Город" {{ isset($disabled) ? 'disabled' : 'required' }}/>
        <input type="text" name="recipient_legal_address" maxlength="190" value="{{ old('recipient_legal_address') ?? ($order->recipient_legal_address ?? '') }}" class="form-control form-item req"  placeholder="Адрес" {{ isset($disabled) ? 'disabled' : 'required' }}/>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_inn">ИНН*</label>
    <div class="col calc__inpgrp"><input type="text" maxlength="12" id="recipient_inn" name="recipient_inn" value="{{ old('recipient_inn') ?? ($order->recipient_inn ?? '') }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_kpp">КПП*</label>
    <div class="col calc__inpgrp"><input type="text" maxlength="9" id="recipient_kpp" name="recipient_kpp" value="{{ old('recipient_kpp') ?? ($order->recipient_kpp ?? '') }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_contact_person_legal">Контактное лицо*</label>
    <div class="col calc__inpgrp"><input type="text" id="recipient_contact_person_legal" class="form-control req" name="recipient_contact_person_legal" value="{{ old('recipient_contact_person_legal') ?? ($order->recipient_contact_person ?? '') }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_phone_legal">Телефон*</label>
    <div class="col calc__inpgrp"><input type="text" placeholder="+7(XXX)XXX-XX-XX" class="form-control req phone-mask" id="recipient_phone_legal" name="recipient_phone_legal" value="{{ old('recipient_phone_legal') ?? ($order->recipient_phone ?? '') }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_addition_info_legal">Дополнительная информация</label>
    <div class="col"><input type="text" maxlength="500" id="recipient_addition_info_legal" name="recipient_addition_info_legal" value="{{ old('recipient_addition_info_legal') ?? ($order->recipient_addition_info ?? '') }}" class="form-control" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>
