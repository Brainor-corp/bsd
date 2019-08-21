<div class="mb-3">
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link {{ \Request::route()->getName() == 'profile-data-show' ? 'active' : '' }}" id="nav-home-tab" href="{{ route('profile-data-show') }}" role="tab">Профиль</a>
        <a class="nav-item nav-link {{ \Request::route()->getName() == 'profile-balance-show' ? 'active' : '' }}" id="nav-profile-tab" href="{{ route('profile-balance-show') }}" role="tab">Баланс</a>
        <a class="nav-item nav-link {{ \Request::route()->getName() == 'profile-contract-show' ? 'active' : '' }}" id="nav-contact-tab" href="{{ route('profile-contract-show') }}" role="tab">Договор</a>
    </div>
</div>