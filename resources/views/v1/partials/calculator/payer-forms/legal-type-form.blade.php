<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_legal_form">Правовая форма*</label>
    <div class="col calc__inpgrp"><input type="text" id="payer_legal_form" value="{{ $order->payer_legal_form ?? '' }}" name="payer_legal_form" class="form-control req" placeholder="ИП, ООО, АО" {{ isset($disabled) ? 'disabled' : 'required' }} /></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_company_name">Название организации*</label>
    <div class="col"><input type="text" id="payer_company_name" name="payer_company_name" value="{{ $order->payer_company_name ?? '' }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }} /></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Юридический адрес*</label>
    <div class="col">
        <input type="text" name="payer_legal_address_city" value="{{ $order->payer_legal_address_city ?? '' }}" class="form-control form-item req"  placeholder="Город" {{ isset($disabled) ? 'disabled' : 'required' }}/>
        <input type="text" name="payer_legal_address_street" value="{{ $order->payer_legal_address_street ?? '' }}" class="form-control form-item req"  placeholder="Улица" {{ isset($disabled) ? 'disabled' : 'required' }}/>
        <div class="input-group">
            <input type="text" name="payer_legal_address_house" value="{{ $order->payer_legal_address_house ?? '' }}" class="form-control text-center form-item" placeholder="Дом" {{ isset($disabled) ? 'disabled' : '' }}/>
            <input type="text" name="payer_legal_address_block" value="{{ $order->payer_legal_address_block ?? '' }}" class="form-control text-center form-item" placeholder="Корп." {{ isset($disabled) ? 'disabled' : '' }}/>
            <input type="text" name="payer_legal_address_building" value="{{ $order->payer_legal_address_building ?? '' }}" class="form-control text-center form-item" placeholder="Стр." {{ isset($disabled) ? 'disabled' : '' }}/>
            <input type="text" name="payer_legal_address_apartment" value="{{ $order->payer_legal_address_apartment ?? '' }}" class="form-control text-center" placeholder="Кв./оф." {{ isset($disabled) ? 'disabled' : '' }}/>
        </div>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_inn">ИНН*</label>
    <div class="col calc__inpgrp"><input type="text" id="payer_inn" name="payer_inn" value="{{ $order->payer_inn ?? '' }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_kpp">КПП*</label>
    <div class="col calc__inpgrp"><input type="text" id="payer_kpp" name="payer_kpp" value="{{ $order->payer_kpp ?? '' }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_name">Контактноя лицо*</label>
    <div class="col calc__inpgrp"><input type="text" id="payer_name" class="form-control req" name="payer_name" value="{{ $order->payer_name ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_phone">Телефон*</label>
    <div class="col calc__inpgrp"><input type="text" class="form-control req" id="payer_phone" name="payer_phone" value="{{ $order->payer_phone ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="payer_addition_info">Дополнительная информация</label>
    <div class="col"><input type="text" id="payer_addition_info" name="payer_addition_info" value="{{ $order->payer_addition_info ?? '' }}" class="form-control" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>