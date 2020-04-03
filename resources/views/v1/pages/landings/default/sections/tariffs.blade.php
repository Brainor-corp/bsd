<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="w-100 border p-3 mb-3 bg-light">
                    <h4 style="text-align: center;">Тарифы на грузоперевозки маршрута <strong>{{ $route->real_arrow_name }}</strong></h4>

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
                                    <span>Бандероль</span> <br>
                                    <span style="white-space: nowrap;">до 0,01 м<sup>3</sup> до 2 кг</span>
                                </td>
                                <td rowspan="{{ $rowSpan }}" class="align-middle">
                                    <span>мин. стоимость руб.</span>
                                </td>
                                @if($route->route_tariffs->where('rate.slug', 'ves')->count() > 0)
                                    <td colspan="{{ $route->route_tariffs->where('rate.slug', 'ves')->count() }}">стоимость за 1кг руб.</td>
                                @endif
                                @if($route->route_tariffs->where('rate.slug', 'obem')->count() > 0)
                                    <td colspan="{{ $route->route_tariffs->where('rate.slug', 'obem')->count() }}">стоимость за 1м <sup>3</sup> руб.</td>
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
            </div>
        </div>
    </div>
</section>
