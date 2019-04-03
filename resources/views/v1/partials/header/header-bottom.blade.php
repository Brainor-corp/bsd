<div class="row">
    <div class="col-12 col-xl-7 d-flex flex-column header__main_left whiteTxtColor">
        <div class="logo_inner"></div>
        <h1>Доставка любых грузов</h1>
        <p>по всей России в более чем 300 городов</p>
        <ul class="icons_list clearfix text-center text-md-left">
            <li data-toggle="tooltip" data-placement="bottom" title="Морские перевозки">
                <a href="##" class="service__mini-block mezhter"></a>
            </li>
            <li data-toggle="tooltip" data-placement="bottom" title="Авиа перевозки">
                <a href="##" class="service__mini-block aviap"></a>
            </li>
            <li data-toggle="tooltip" data-placement="bottom" title="Наземные перевозки">
                <a href="##" class="service__mini-block perevozkadoc"></a>
            </li>
            <li data-toggle="tooltip" data-placement="bottom" title="ЖД перевозки">
                <a href="##" class="service__mini-block dostavkavgip"></a>
            </li>
        </ul>
    </div>
    <div class="col-xl-5 col-lg-12">
        <form class="calculator-form" action="/calculator-show" method="post">
            <div class="calc__block mr-auto mr-md-0 ml-auto">
                <div class="calc__block_title">Быстрый расчет стоимости</div>
                <div class="input-group calc__block_inpg">
                    {{--<input type="text" id="ship_city" class="form-control" placeholder="Москва">--}}
                    <select id="ship_city" class="form-control" name="ship_city" placeholder="Москва">
                        <option value=""></option>
                        @if($shipCities->count() > 0)
                            @foreach($shipCities as $shipCity)
                                <option value="{{ $shipCity->id }}" @if($shipCity->id == 53) selected @endif>{{ $shipCity->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <a href="#" class="group-input__icon"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                    {{--<input type="text" id="dest_city" class="form-control" placeholder="Санкт-Петербург">--}}
                    <select id="dest_city" class="form-control"  name="dest_city" placeholder="Москва">
                        <option value=""></option>
                        @if($destinationCities->count() > 0)
                            @foreach($destinationCities as $destinationCity)
                                <option value="{{ $destinationCity->id }}" @if($destinationCity->id == 78) selected @endif>{{ $destinationCity->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-item d-flex align-items-center justify-content-between calc__block_inpg">
                    <label class="calc__label_max">Габариты (м)</label>
                    <div class="input-group">
                        <input type="number" step="any" name="packages[0][length]" class="form-control text-center package-params" placeholder="Д" value="0.1">
                        <input type="number" step="any" name="packages[0][width]" class="form-control text-center package-params" placeholder="Ш" value="0.1">
                        <input type="number" step="any" name="packages[0][height]" class="form-control text-center package-params" placeholder="В" value="0.1">
                    </div>
                </div>
                <div class="form-item d-flex align-items-center justify-content-between calc__block_inpg">
                    <label class="calc__label_max">Вес груза (кг)</label>
                    <input type="number" step="any" name="packages[0][weight]" class="form-control text-center package-params" placeholder="Вес в кг." value="1">
                </div>
                <div class="form-inline calc__block_itog" >
                    <div class="form-item justify-content-between w-100">
                        Срок доставки
                        <span><span id="delivery-time">1</span><span class="rouble"> дней</span></span>
                    </div>
                    <div class="form-item justify-content-between w-100">
                        Стоимость перевозки
                        <span><span id="base-price">150</span><span class="rouble">p</span></span>
                    </div>
                </div>
                <button class="btn btn-block btn-danger">Перейти к оформлению заказа</button>
            </div>
            @csrf
        </form>
    </div>
</div>
<div class="row event-feed__list">
    <div class="event-feed__item col-lg-4 d-flex align-items-center">
        <div class="event-feed__icon">
            <i class="fa fa-exclamation-circle"></i>
        </div>
        <div class="event-feed__info">
            <span>С 01.01.2019 мы меняем плотность груза, а также стоимость услуг по некоторым направлениям</span>
        </div>
    </div>
    <div class="event-feed__item col-lg-4 d-flex align-items-center">
        <div class="event-feed__icon">
            <i class="fa fa-star"></i>
        </div>
        <div class="event-feed__info">
            <span>С 01.01.2019 мы меняем плотность груза, а также стоимость услуг по некоторым направлениям</span>
        </div>
    </div>
    <div class="event-feed__item col-lg-4 d-flex align-items-center">
        <div class="event-feed__icon">
            <i class="fa fa-star"></i>
        </div>
        <div class="event-feed__info">
            <span>С 01.01.2019 мы меняем плотность груза, а также стоимость услуг по некоторым направлениям</span>
        </div>
    </div>
</div>