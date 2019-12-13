<p style="text-align: center;">
    Данный прайс-лист является приложением № 3 Договора на
    Транспортно-Экспедиционные услуги ООО "Балтийская Служба Доставки"
</p>
@if(isset($routes))
    @foreach($routes as $route)
        <div class="w-100 border p-3 mb-3 {{ $loop->index % 2 == 0 ? 'bg-light' : '' }}">
            <h4 style="text-align: center;">Тарифы на грузоперевозки маршрута <strong>{{ $route->name }}</strong></h4>

            <div class="table-responsive mt-3 mb-3">
                <table class="table table-bordered text-center">
                    <thead>
                    @php
                        $rowSpan = 1;
                        if(
                            $route->route_tariffs->where('rate.slug', 'ves')->count() > 0 ||
                            $route->route_tariffs->where('rate.slug', 'obem')->count() > 0
                        ) {
                            $rowSpan = 2;
                        }
                    @endphp
                    <tr>
                        <td rowspan="{{ $rowSpan }}" class="align-middle">
                            <span>Бандероль до 0,01 м3 до 2 кг</span>
                        </td>
                        <td rowspan="{{ $rowSpan }}" class="align-middle">
                            <span>мин. стоимость руб.</span>
                        </td>
                        @if($route->route_tariffs->where('rate.slug', 'ves')->count() > 0)
                            <td colspan="{{ $route->route_tariffs->where('rate.slug', 'ves')->count() }}">стоимость за 1кг руб.</td>
                        @endif
                        @if($route->route_tariffs->where('rate.slug', 'obem')->count() > 0)
                            <td colspan="{{ $route->route_tariffs->where('rate.slug', 'obem')->count() }}">стоимость за 1м3 руб.</td>
                        @endif
                        <td rowspan="{{ $rowSpan }}" class="align-middle">
                            <span>мин. срок доставки</span>
                        </td>
                    </tr>
                    @if(
                        $route->route_tariffs->where('rate.slug', 'ves')->count() ||
                        $route->route_tariffs->where('rate.slug', 'obem')->count()
                    )
                        <tr>
                            @foreach($route->route_tariffs->where('rate.slug', 'ves')->sortBy('threshold.value') as $routeTariff)
                                <td class="align-middle">
                                    До {{ (float)($routeTariff->threshold->value) }}кг
                                </td>
                            @endforeach
                            @foreach($route->route_tariffs->where('rate.slug', 'obem')->sortBy('threshold.value') as $routeTariff)
                                <td class="align-middle">
                                    До {{ (float)($routeTariff->threshold->value) }}м<sup>3</sup>
                                </td>
                            @endforeach
                        </tr>
                    @endif
                    </thead>
                    <tbody>
                    <tr>
                        <th class="align-middle">
                            {{ $route->wrapper_tariff }}
                        </th>
                        <th class="align-middle">
                            {{ $route->min_cost }}
                        </th>
                        @foreach($route->route_tariffs->where('rate.slug', 'ves') as $routeTariff)
                            <th class="align-middle">{{ (float)($routeTariff->price) }}</th>
                        @endforeach
                        @foreach($route->route_tariffs->where('rate.slug', 'obem') as $routeTariff)
                            <th class="align-middle">{{ (float)($routeTariff->price) }}</th>
                        @endforeach
                        <th class="align-middle">
                            {{ $route->delivery_time }}
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@else
    <span>Информация по маршрутам выбранных городов отсутствует</span>
@endif

@if(!empty($insideForwardings->groupBy('city_id')))
    @foreach($insideForwardings->groupBy('city_id') as $insideForwardingsCities)
        <h4 style="text-align: center;">Стоимость экспедирования в черте города {{ $insideForwardingsCities->first()->city->name }}</h4>
        <div class="table-responsive mb-3">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <td class="align-middle">Город</td>
                    @foreach($insideForwardingsCities
                        ->sortBy('forwardThreshold.weight')
                        ->sortBy('forwardThreshold.volume') as $insideForwarding
                    )
                        <td class="align-middle">
                            {{ $loop->first ? $insideForwarding->forwardThreshold->name : str_replace('1-', 'До ', $insideForwarding->forwardThreshold->name) }}
                        </td>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th class="align-middle">{{ $insideForwardingsCities->first()->city->name }}</th>
                    @foreach($insideForwardingsCities
                        ->sortBy('forwardThreshold.weight')
                        ->sortBy('forwardThreshold.volume') as $insideForwarding
                    )
                        <th class="align-middle">
                            {{ $insideForwarding->tariff }}
                        </th>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>
    @endforeach
@else
    <span>Информация по стоимости экспедирования в выбранных городах отсутствует</span>
@endif

<br>

<div style="page-break-before: auto; page-break-inside: avoid;">
    <h4 style="text-align: center;">
        Дополнительные услуги:
    </h4>
    <p class="mb-2">
        Предоставление услуги «Личный кабинет», оформление сопроводительных документов в пункте приема груза,
        электронный документооборот, СМС-информирование о статусах груза – 50 руб.
        (Стоимость не распространяется на минимальную стоимость доставки грузов)
    </p>
    <p class="mb-2">
        До упаковка:  400 руб за 1 м3 или мешок; обрешетка груза: 600 руб. за 1м3+200руб./паллет (мин. 200 руб.);
        паллетирование - 200 руб., пломбирование: 50 руб. за 1 место
    </p>
    <p class="mb-2">
        При оказании услуг паллетирования, обрешетки грузовых мест,
        указанная стоимость не включает стоимость паллета- (200 руб.) Объем при этом рассчитывается с поправочным коэф. 30%.
    </p>
    <p class="mb-2">
        Возврат сопроводительных док-в на груз отправителю (ТТН, накладных, счет-фактур) с отметкой получателя,
        в том числе из Гипер Маркетов: (услуга действует при доставке по адресу) - 200 руб.
    </p>
    <p class="mb-2">
        Страхование груза: стоимость страхового полиса 0,1% от стоимости указанной в ТН,
        либо от объявленной стоимости груза грузоотправителем.
    </p>
    <p class="mb-2">
        Сдача груза с внутри тарным пересчетом мест и сверкой их с ТН отправителя -5 руб./ед. вложения
    </p>
    <p class="mb-2">
        Хранение на складе Экспедитора: 2 суток с момента прибытия груза и уведомления Грузополучателя -бесплатно;
        далее - 35коп./кг, 70руб./м3 в сутки, но не менее 100 руб.
    </p>
    <p class="mb-2">
        В случае ответственного хранения погрузо-разгрузочные операции оплачиваются отдельно: за 1000 кг./5м3 -1000 руб,
        но не менее 500 руб.(включена погрузка и выгрузка)
    </p>
    <p class="mb-2">
        Страхование груза: стоимость страхового полиса 0,15% от стоимости указанной в ТН,
        либо от объявленной стоимости груза грузоотправителем.
    </p>
    <p class="mb-2">
        Сдача груза с внутри тарным пересчетом мест и сверкой их с ТН отправителя -5 руб./ед. вложения
    </p>
    <p class="mb-2">
        Хранение на складе Экспедитора: 2 суток с момента прибытия груза и уведомления Грузополучателя -бесплатно;
        далее - 35коп./кг, 70руб./м3 в сутки, но не менее 100 руб.
    </p>
    <p class="mb-4">
        В случае ответственного хранения погрузо-разгрузочные операции оплачиваются отдельно: за 1000 кг./5м3 -1000 руб.,
        но не менее 500 руб.(включена погрузка и выгрузка)
    </p>
</div>
