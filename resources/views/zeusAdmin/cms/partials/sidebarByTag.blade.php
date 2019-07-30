@php
    $menu = $page->tags->where('slug', 'like', 'sidebar');
        $menu = $page->tags->filter(function ($value, $key) {
        $test = strpos($value->slug, 'sidebar-menu');
        if($test !== false){return $value;}
        //return $value;
    });
    $menu = $menu->first();
@endphp
@if($menu)

    @php($sidebarMenu = \Zeus\Admin\Cms\Helpers\MenuHelper::getMenuTreeBySlug($menu->slug))

    <div class="sidebar__title">{{ $menu->description }}</div>
    <nav class="section">
        <ul class="sidebar__menu">
            @foreach($sidebarMenu as $element)
            <li class="sidebar__item"><a href="{{ $element->url }}">{{ $element->title }}</a></li>
            @endforeach
        </ul>
    </nav>

@endif
<section class="section">
    <div class="order__block d-flex flex-column" style="border:none;">
        <img src="images/img/delivery-img.png" alt="">
        <div class="sidebar__item "><a href="{{ url('calculator-show') }}">Оформить заказ на доставку</a></div>
    </div>
</section>