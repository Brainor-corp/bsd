<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_legal_form">Правовая форма*</label>
    <div class="col calc__inpgrp"><input type="text" id="sender_legal_form" value="{{ $order->sender_legal_form ?? '' }}" name="sender_legal_form" class="form-control req" placeholder="ИП, ООО, АО" {{ isset($disabled) ? 'disabled' : 'required' }} /></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_company_name">Название организации*</label>
    <div class="col"><input type="text" id="sender_company_name" name="sender_company_name" value="{{ $order->sender_company_name ?? '' }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }} /></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label">Юридический адрес*</label>
    <div class="col">
        <input type="text" name="sender_legal_address_city" value="{{ $order->sender_legal_address_city ?? '' }}" class="form-control form-item req"  placeholder="Город" {{ isset($disabled) ? 'disabled' : 'required' }}/>
        <input type="text" name="sender_legal_address_street" value="{{ $order->sender_legal_address_street ?? '' }}" class="form-control form-item req"  placeholder="Улица" {{ isset($disabled) ? 'disabled' : 'required' }}/>
        <div class="input-group">
            <input type="text" name="sender_legal_address_house" value="{{ $order->sender_legal_address_house ?? '' }}" class="form-control text-center form-item" placeholder="Дом" {{ isset($disabled) ? 'disabled' : '' }}/>
            <input type="text" name="sender_legal_address_block" value="{{ $order->sender_legal_address_block ?? '' }}" class="form-control text-center form-item" placeholder="Корп." {{ isset($disabled) ? 'disabled' : '' }}/>
            <input type="text" name="sender_legal_address_building" value="{{ $order->sender_legal_address_building ?? '' }}" class="form-control text-center form-item" placeholder="Стр." {{ isset($disabled) ? 'disabled' : '' }}/>
            <input type="text" name="sender_legal_address_apartment" value="{{ $order->sender_legal_address_apartment ?? '' }}" class="form-control text-center" placeholder="Кв./оф." {{ isset($disabled) ? 'disabled' : '' }}/>
        </div>
    </div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_inn">ИНН*</label>
    <div class="col calc__inpgrp"><input type="text" id="sender_inn" name="sender_inn" value="{{ $order->sender_inn ?? '' }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_kpp">КПП*</label>
    <div class="col calc__inpgrp"><input type="text" id="sender_kpp" name="sender_kpp" value="{{ $order->sender_kpp ?? '' }}" class="form-control req" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_name">Контактноя лицо*</label>
    <div class="col calc__inpgrp"><input type="text" id="sender_name" class="form-control req" name="sender_name" value="{{ $order->sender_name ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_phone">Телефон*</label>
    <div class="col calc__inpgrp"><input type="text" class="form-control req" id="sender_phone" name="sender_phone" value="{{ $order->sender_phone ?? '' }}" {{ isset($disabled) ? 'disabled' : 'required' }}/></div>
</div>
<div class="form-item row align-items-center">
    <label class="col-auto calc__label" for="sender_addition_info">Дополнительная информация</label>
    <div class="col"><input type="text" id="sender_addition_info" name="sender_addition_info" value="{{ $order->sender_addition_info ?? '' }}" class="form-control" {{ isset($disabled) ? 'disabled' : '' }}/></div>
</div>