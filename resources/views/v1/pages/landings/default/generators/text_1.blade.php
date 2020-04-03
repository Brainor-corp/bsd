<p style="text-align: center;"><span style="font-size:24px;"><strong>Доставка грузов по маршруту {{ $route->$route->real_arrow_name }}</strong></span></p>

<p>&nbsp;</p>

<p>
    <span style="font-size:16px;">
        <span style="color:#5a666e;">Балтийская Служба Доставки осуществляет грузоперевозки по маршруту {{ $route->$route->real_arrow_name }}. </span>
        <span style="color:#5a666e;">Стоимость доставки груза - от {{ $route->min_cost }} руб.
            <br>Постоянное наличие нескольких машин на маршруте позволяет доставлять товары в точно срок.
            Срок доставки грузов - от {{ $route->delivery_time }} {{ \App\Http\Helpers\TextHelper::daysTitleByCount($route->delivery_time) }}.
        </span>
    </span>
</p>
