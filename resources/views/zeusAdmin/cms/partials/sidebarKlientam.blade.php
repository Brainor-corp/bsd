@php($sidebarMenu = \Zeus\Admin\Cms\Helpers\MenuHelper::getMenuTreeBySlug('saydbar-klientam'))

<div class="sidebar__title">Услуги</div>
<nav class="section">
    <ul class="sidebar__menu">
        @foreach($sidebarMenu as $element)
        <li class="sidebar__item"><a href="{{ $element->url }}">{{ $element->title }}</a></li>
        @endforeach
    </ul>
</nav>
<section class="section">
    <div class="order__block d-flex flex-column" style="border:none;">
        <img src="images/img/delivery-img.png" alt="">
        <div class="sidebar__item "><a href="{{ url('calculator-show') }}">Оформить заказ на доставку</a></div>
    </div>
</section>
{{--<section class="section">--}}
    {{--<div class="sidebar-stock__item d-flex flex-column justify-content-between sidebar-stock-img">--}}
        {{--<span class="sidebar-stock__discount d-flex justify-content-center"><span class="amount">25</span><span class="symbol">%</span></span>--}}
        {{--<span class="sidebar-stock__title">Скидка 5% на<br />меж терминальную<br />перевозку груза</span>--}}
        {{--<span class="sidebar-stock__duration"><i class="fa fa-calendar"></i>с 01.05.2016 по 31.08.2016</span>--}}
    {{--</div>--}}
{{--</section>--}}
</div>