<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_legal_form">Правовая форма*</label>
    <div class="col calc__inpgrp"><input type="text" id="recipient_legal_form" value="{{ $order->recipient_legal_form ?? '' }}" name="recipient_legal_form" class="form-control req" placeholder="ИП, ООО, АО" {{ isset($disabled) ? 'disabled' : 'required' }} /></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_company_name">Название организации*</label>
    <div class="col"><input type="text" id="recipient_company_name" name="recipient_company_name" value="{{ $order->recipient_company_name ?? '' }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }} /></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Юридический адрес*</label>
    <div class="col">
        <input type="text" name="recipient_legal_address_city" value="{{ $order->recipient_legal_address_city ?? '' }}" class="form-control form-item req"  placeholder="Город" {{ isset($disabled) ? 'disabled' : 'required' }}/>
        <input type="text" name="recipient_legal_address_street" value="{{ $order->recipient_legal_address_street ?? '' }}" class="form-control form-item req"  placeholder="Улица" {{ isset($disabled) ? 'disabled' : 'required' }}/>
        <div class="input-group">
            <input type="text" name="recipient_legal_address_house" value="{{ $order->recipient_legal_address_house ?? '' }}" class="form-control text-center form-item" placeholder="Дом" {{ isset($disabled) ? 'disabled' : '' }}/>
            <input type="text" name="recipient_legal_address_block" value="{{ $order->recipient_legal_address_block ?? '' }}" class="form-control text-center form-item" placeholder="Корп." {{ isset($disabled) ? 'disabled' : '' }}/>
            <input type="text" name="recipient_legal_address_building" value="{{ $order->recipient_legal_address_building ?? '' }}" class="form-control text-center form-item" placeholder="Стр." {{ isset($disabled) ? 'disabled' : '' }}/>
            <input type="text" name="recipient_legal_address_apartment" value="{{ $order->recipient_legal_address_apartment ?? '' }}" class="form-control text-center" placeholder="Кв./оф." {{ isset($disabled) ? 'disabled' : '' }}/>
        </div>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_inn">ИНН*</label>
    <div class="col calc__inpgrp"><input type="text" id="recipient_inn" name="recipient_inn" value="{{ $order->recipient_inn ?? '' }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_kpp">КПП*</label>
    <div class="col calc__inpgrp"><input type="text" id="recipient_kpp" name="recipient_kpp" value="{{ $order->recipient_kpp ?? '' }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_contact_person_legal">Контактноя лицо*</label>
    <div class="col calc__inpgrp"><input type="text" id="recipient_contact_person_legal" class="form-control req" name="recipient_contact_person_legal" value="{{ $order->recipient_contact_person ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_phone_legal">Телефон*</label>
    <div class="col calc__inpgrp"><input type="text" class="form-control req" id="recipient_phone_legal" name="recipient_phone_legal" value="{{ $order->recipient_phone ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="recipient_addition_info_legal">Дополнительная информация</label>
    <div class="col"><input type="text" id="recipient_addition_info_legal" name="recipient_addition_info_legal" value="{{ $order->recipient_addition_info ?? '' }}" class="form-control" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>