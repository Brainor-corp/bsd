<div class="mb-3">
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link {{ \Request::route()->getName() == 'profile-data-show' ? 'active' : '' }}" id="nav-home-tab" href="{{ route('profile-data-show') }}" role="tab">Профиль</a>
        <a class="nav-item nav-link {{ \Request::route()->getName() == 'profile-balance-show' ? 'active' : '' }}" id="nav-profile-tab" href="{{ route('profile-balance-show') }}" role="tab">Баланс</a>
        <a class="nav-item nav-link {{ \Request::route()->getName() == 'profile-contract-show' ? 'active' : '' }}" id="nav-contact-tab" href="{{ route('profile-contract-show') }}" role="tab">Договор</a>

        <a class="ml-auto nav-item nav-link border-0" href="{{ route('calculator-show', ['id' => null, 'type' => 'calculator']) }}" >Расчет стоимости</a>
        <a class="nav-item nav-link border-0" href="{{ route('calculator-show', ['id' => null, 'type' => 'order']) }}" >Оформить новую заявку</a>
    </div>
</div>
