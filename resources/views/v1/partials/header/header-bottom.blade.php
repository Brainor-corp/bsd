<div class="row">
    <div class="col-12 col-xl-7 d-flex flex-column header__main_left whiteTxtColor">
        <div class="logo_inner"></div>
        <h1>Доставка любых грузов</h1>
        <p>по всей России в более чем 300 городов</p>
        <ul class="icons_list clearfix text-center text-md-left">
            <li data-toggle="tooltip" data-placement="bottom" title="Морские перевозки">
                <a href="/uslugi/morskie-perevozki" class="service__mini-block mezhter"></a>
            </li>
            <li data-toggle="tooltip" data-placement="bottom" title="Авиа перевозки">
                <a href="/uslugi/aviaperevozka" class="service__mini-block aviap"></a>
            </li>
            <li data-toggle="tooltip" data-placement="bottom" title="Наземные перевозки">
                <a href="/uslugi/nazemnye-perevozki" class="service__mini-block perevozkadoc"></a>
            </li>
            <li data-toggle="tooltip" data-placement="bottom" title="ЖД перевозки">
                <a href="/uslugi/zhd-perevozki" class="service__mini-block dostavkavgip"></a>
            </li>
        </ul>
    </div>
    <div class="col-xl-5 col-lg-12">
        <form class="short_calculator-form" action="/calculator-show" method="get">
            <div class="calc__block mr-auto mr-md-0 ml-lg-auto">
                <div class="calc__block_title">Быстрый расчет стоимости</div>
                <div class="input-group calc__block_inpg">
                    {{--<input type="text" id="ship_city" class="form-control" placeholder="Москва">--}}
                    <select id="short_ship_city" class="form-control" name="ship_city" placeholder="Выбрать город">
                        <option value=""></option>
                        @if($shipCities->count() > 0)
                            @foreach($shipCities as $shipCity)
                                <option value="{{ $shipCity->name }}" @if($shipCity->name === 'Москва') selected @endif>{{ $shipCity->name }}</option>
                            @endforeach
                        @endif
                    </select>
{{--                    <a href="#" class="group-input__icon"><i class="fa fa-refresh" aria-hidden="true"></i></a>--}}
                    {{--<input type="text" id="dest_city" class="form-control" placeholder="Санкт-Петербург">--}}
                    <select id="short_dest_city" class="form-control"  name="dest_city" placeholder="Выбрать город">
                        <option value=""></option>
                        @if($destinationCities->count() > 0)
                            @foreach($destinationCities as $destinationCity)
                                <option value="{{ $destinationCity->name }}" @if($destinationCity->name === 'Санкт-Петербург') selected @endif>{{ $destinationCity->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-item align-items-center justify-content-between calc__block_inpg">
                    <div class="row align-items-center">
                        <div class="col-lg-5 col-12">
                            <label class="calc__label_max">Вес груза (кг)</label>
                        </div>
                        <div class="col">
                            <input type="number" min="0" step="any" name="cargo[total_weight]" class="form-control text-center short_package-weight" placeholder="Вес в кг." value="1">
                        </div>
                    </div>
                </div>
                <div class="form-item align-items-center justify-content-between calc__block_inpg">
                    <div class="row align-items-center">
                        <div class="col-lg-5 col-12">
                            <label class="calc__label_max">Объем груза (м<sup>3</sup>)</label>
                        </div>
                        <div class="col">
                            <input type="number" min="0" step="any" id="short_packages_0_volume" class="form-control text-center package-params short_package-volume" name="cargo[total_volume]" data-package-id="0" value="0.01"/>
                        </div>
                    </div>
                </div>
                <div class="form-inline calc__block_itog" >
                    <div class="form-item justify-content-between w-100 short_ins_wrapper">
                        Страхование
                        <span><span id="short_ins">-</span><span class="rouble">p</span></span>
                    </div>
                    <div class="form-item justify-content-between w-100">
                        Стоимость перевозки
                        <span><span id="short_base-price">-</span><span class="rouble">p</span></span>
                    </div>
                    <div class="form-item justify-content-between w-100" style="display: none" id="deliveryPriceBlock">
                        Стоимость забора "от двери" (<span id="deliveryDistance"></span>)
                        <span id="deliveryPrice"></span><span class="rouble">p</span>
                    </div>
                    <div class="form-item justify-content-between w-100" style="display: none" id="bringPriceBlock">
                        Стоимость доставки "до двери" (<span id="bringDistance"></span>)
                        <span id="bringPrice"></span><span class="rouble">p</span>
                    </div>
                    <div class="form-item justify-content-between w-100">
                        Итог
                        <span><span id="short_total-price">-</span><span class="rouble">p</span></span>
                    </div>
                </div>
                <button class="btn btn-block btn-danger">Перейти к оформлению заказа</button>
            </div>
        </form>
    </div>
</div>
@if(count($news)>0)
    <div class="row event-feed__list">
        @foreach($news as $new)
            <div class="event-feed__item col-lg-4 d-flex align-items-center">
                <div class="event-feed__icon">
                    <i class="fa fa-exclamation-circle"></i>
                </div>
                <div class="event-feed__info">
                    <span><a href="{{ route('news-single-show', ['slug' => $new->slug]) }}">{{ $new->description }}</a></span>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="mb-4"></div>
@endif
