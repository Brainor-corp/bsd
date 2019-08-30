@if(isset($routes))
    @foreach($routes as $route)
        <div class="w-100 border p-3 mb-3 {{ $loop->index % 2 == 0 ? 'bg-light' : '' }}">
            <h5>Тарифы на грузоперевозки маршрута <strong>{{ $route->name }}</strong></h5>

            <div class="table-responsive mt-3 mb-3">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <td rowspan="2" class="align-middle">
                            <span>Бандероль до 0,01 м3 до 2 кг</span>
                        </td>
                        <td rowspan="2" class="align-middle">
                            <span>мин. стоимость руб.</span>
                        </td>
                        @if($route->route_tariffs->where('rate.slug', 'ves')->count() > 0)
                            <td colspan="{{ $route->route_tariffs->where('rate.slug', 'ves')->count() }}">стоимость за 1кг руб.</td>
                        @endif
                        @if($route->route_tariffs->where('rate.slug', 'obem')->count() > 0)
                            <td colspan="{{ $route->route_tariffs->where('rate.slug', 'obem')->count() }}">стоимость за 1м3 руб.</td>
                        @endif
                        <td rowspan="2" class="align-middle">
                            <span>мин. срок доставки*</span>
                        </td>
                    </tr>
                    @if(
                        $route->route_tariffs->where('rate.slug', 'ves')->count() ||
                        $route->route_tariffs->where('rate.slug', 'obem')->count()
                    )
                        <tr>
                            @foreach($route->route_tariffs->where('rate.slug', 'ves') as $routeTariff)
                                <td class="align-middle">До {{ $routeTariff->threshold->value }}кг</td>
                            @endforeach
                            @foreach($route->route_tariffs->where('rate.slug', 'obem') as $routeTariff)
                                <td class="align-middle">
                                    До {{ $routeTariff->threshold->value }}м<sup>3</sup>
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
                            <th class="align-middle">{{ $routeTariff->price }}</th>
                        @endforeach
                        @foreach($route->route_tariffs->where('rate.slug', 'obem') as $routeTariff)
                            <th class="align-middle">{{ $routeTariff->price }}</th>
                        @endforeach
                        <th class="align-middle">
                            {{ $route->delivery_time }}
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h5>Стоимость экспедирования в черте города</h5>
            @forelse($insideForwardings->whereIn('city_id', [$route->ship_city_id, $route->dest_city_id])->groupBy('city_id') as $insideForwardingsCities)
                <div class="table-responsive {{ $loop->last ? '' : 'mb-3' }}">
                    <table class="table table-bordered text-center">
                        <thead>
                        <tr>
                            <td class="align-middle">Город</td>
                            @foreach($insideForwardingsCities as $insideForwarding)
                                <td class="align-middle">
                                    {{ $insideForwarding->forwardThreshold->name }}
                                </td>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th class="align-middle">{{ $insideForwardingsCities->first()->city->name }}</th>
                            @foreach($insideForwardingsCities as $insideForwarding)
                                <th class="align-middle">
                                    {{ $insideForwarding->tariff }}
                                </th>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>
            @empty
                <span>Информация отсутствует</span>
            @endforelse
        </div>
    @endforeach
@else
    <span>Информация по выбранным городам отсутствует</span>
@endif