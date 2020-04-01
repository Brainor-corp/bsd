<h1 style="text-align: center;"><span style="color:#5a666e;"><strong><span style="font-size:24px;">Доставка грузов по маршруту {{ $route->name }}</span></strong></span></h1>

<p>&nbsp;</p>

<p>
    <span style="font-size:16px;">
        <span style="color:#5a666e;">Балтийская Служба Доставки осуществляет грузоперевозки по маршруту {{ $route->name }}. </span>
        <span style="color:#5a666e;">Стоимость доставки груза - &nbsp;от {{ $route->min_cost }} руб. <br>Постоянное наличие нескольких машин на маршруте позволяет доставлять товары в точно срок. Срок доставки грузов &nbsp;&ndash; от {{ $route->delivery_time }} {{ \App\Http\Helpers\TextHelper::daysTitleByCount($route->delivery_time) }}.
        </span>
    </span>
</p>
