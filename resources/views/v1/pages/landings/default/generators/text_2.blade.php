<p style="text-align: center;">
    <span style="font-size:24px;">
        <strong>
            ООО &quot;Балтийская Служба Доставки&quot; с гордостью заявляет
            о своих преимуществах и возможностях при перевозках грузов по маршруту {{ $route->real_arrow_name }}:
        </strong>
    </span>
</p>

<p>&nbsp;</p>

<p>
    <span style="font-size:16px;">
        <span style="color:#5a666e;">
            - перевозка любых партий груза от 1 кг., как &laquo;россыпью&raquo; так и на паллета местах
        </span>
    </span>
</p>

<p>
    <span style="font-size:16px;">
        <span style="color:#5a666e;">
            - доставка грузов, в городе назначения, как &laquo;до двери&raquo; Клиента, так и &nbsp;в Гипермаркеты и Распределительные центры,
            в указанное Клиентами время авизации, подписанием и возвратом сопроводительной документации
        </span>
    </span>
</p>

<p><span style="font-size:16px;">
        <span style="color:#5a666e;">
            - качественная складская логистика включает в себя не только обработку грузов, их дополнительную упаковку,
            паллетирование, маркировку, но предоставление грузчиков, при необходимости, консолидацию груза, страхование ответственности и сроков доставки.
        </span>
    </span>
</p>

<p>
    <span style="font-size:16px;">
        <span style="color:#5a666e;">
            - возможность отслеживать перемещение груза его своевременной выдачи, получение необходимых бухгалтерских и пр. документов в личном кабинете Компании.
        </span>
    </span>
</p>

<p>
    <span style="font-size:16px;">
        <span style="color:#5a666e;">
            Произвести предварительный расчет стоимости перевозки Вы можете самостоятельно по
            <a href="{{ route('calculator-show') }}">ССЫЛКЕ</a> или позвонив по телефону {{ $route->shipCity->closestTerminal->phone }}
        </span>
    </span>
</p>

<p>
    <span style="font-size:16px;">
        <span style="color:#5a666e;">
            Посмотреть, скачать схему расположения терминальных комплексов в городах
            {{ $route->shipCity->name }} ({{ $route->shipCity->terminals->count() > 0 ? $route->shipCity->terminals->count() : 1 }}
            {{ \App\Http\Helpers\TextHelper::terminalsTitleByCount($route->shipCity->terminals->count() > 0 ? $route->shipCity->terminals->count() : 1) }})
            и {{ $route->destinationCity->name }} ({{ $route->destinationCity->terminals->count() > 0 ? $route->destinationCity->terminals->count() : 1 }}
            {{ \App\Http\Helpers\TextHelper::terminalsTitleByCount($route->destinationCity->terminals->count() > 0 ? $route->destinationCity->terminals->count() : 1) }})
            вы можете в разделе <a href="{{ route('terminals-addresses-show') }}">КОНТАКТЫ</a>.
        </span>
        <span style="color:#5a666e;"></span>
    </span>
</p>
