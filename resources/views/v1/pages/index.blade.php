@extends('v1.layouts.mainPageLayout')


@section('content')
    <section class="service">
        <div class="container">
            <div class="title-inline d-flex align-items-center">
                <div class="margin-item"><h3>Услуги</h3></div>
                <a href="##" class="link-with-dotted margin-item">Все услуги</a>
            </div>
            <div class="row row-item justify-content-md-center">
                <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                    <div class="service__block d-flex relative mezhter">
                        <div class="service__block_body">
                            <div class="service__block_title">Меж-терминальная перевозка</div>
                            <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                    <div class="service__block d-flex relative aviap">

                        <div class="service__block_body">
                            <div class="service__block_title"><a href="#">Авиаперевозка</a></div>
                            <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-item justify-content-md-center">
                <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                    <div class="service__block d-flex relative perevozkadoc">
                        <div class="service__block_body">
                            <div class="service__block_title">Доставка документов</div>
                            <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-9 col-lg-6 service__item">
                    <div class="service__block d-flex relative dostavkavgip">
                        <div class="service__block_body">
                            <div class="service__block_title">Доставка в гипермаркеты</div>
                            <p class="service__block_disc">Перевозка грузов весом от нескольких граммов до 20 тонн между терминалами БСД, расположенными в разных городах　России</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="about-company bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-7">
                    <div class="title-inline d-flex align-items-center">
                        <div class="whiteTxtColor margin-item"><h3>О компании</h3></div>
                        <a class="margin-item darkTxtColor link-with-dotted" href="##">Подробнее</a>
                    </div>
                    <p class="darkTxtColor font-medium mw-95 mb-10">ООО «Балтийская Служба Доставки» заслужила репутацию честного и выгодного партнера на Российском рынке грузоперевозок, что важно для любой компании. Одна из основных задач, которые мы перед собой ставим - минимизация времени, необходимого для оформления груза перед отправкой.</p>
                    <div class="clearfix mb-10">
                        <p class="playfairdisplay d-block float-left font-medium p_ourwork">Наша работа - решение проблем по доставке Вашего груза!</p>
                        <p class="d-block float-left darkTxtColor pr-50 p_ourfils">Наши филиалы находятся в городах: Санкт-Петербург, Москва, Нижний Новгород, Ростов-на-Дону, Таганрог, Астрахань, Волгоград, Краснодар, Пятигорск</p>
                    </div>
                    <p class="darkTxtColor font-medium mw-95">Компания ООО «БСД» также осуществляет автоматическую доставку груза до дверей клиента по Ростовской области, Краснодарскому, Ставропольскому краям и по Северному Кавказу.</p>
                </div>
                <div class="col-sm-12 col-md-5 d-flex">
                    <ul class="list-unsetyled stat_list pt-40 d-flex flex-column">
                        <li class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">9</span>
                            <span class="margin-item">лет занимаеимся<br />грузоперевозками</span>
                        </li>
                        <li class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">114</span>
                            <span class="margin-item">сотрудников<br />работает в штате</span>
                        </li>
                        <li class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">316</span>
                            <span class="margin-item">доставляем грузы<br />по 316 городам</span>
                        </li>
                        <li class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">2 678</span>
                            <span class="margin-item">терминалов по<br />всей России</span>
                        </li>
                        <li class="stat__item d-flex align-items-center">
                            <span class="stat__item-amount margin-item">5 682</span>
                            <span class="margin-item">раза выполнили<br />доставку</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="calculation">
        <div class="container">
            <h3>Расчет стоимости</h3>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <form>
                        <div class="calc__title">Груз</div>
                        <div class="form-item row align-items-center">
                            <label class="col-auto calc__label">Наименование груза*</label>
                            <div class="col"><input type="text" class="form-control" placeholder="email@example.com"></div>
                        </div>
                        <div class="form-item row align-items-center">
                            <label class="col-auto calc__label">Тип груза*</label>
                            <div class="col">
                                <select class="custom-select">
                                    <option disabled selected>Выберите из списка</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Габариты (м)*</label>
                                    <div class="col calc__inpgrp relative row__inf">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" placeholder="Д" />
                                            <input type="text" class="form-control text-center" placeholder="Ш" />
                                            <input type="text" class="form-control text-center" placeholder="В" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Вес груза (кг)*</label>
                                    <div class="col calc__inpgrp"><input type="text" class="form-control" /></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Объем (м<sup>3</sup>)*</label>
                                    <div class="col calc__inpgrp"><input type="text" class="form-control" /></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="d-flex">
                                    <div class="info__icon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <div class="col annotation-text">Габариты груза влияют на расчет стоимости, без их указания стоимость может быть неточной</div>
                                </div>
                            </div>
                        </div>
                        <a href="##" class="add_anotherplace">
							<span class="badge calc_badge">
								<i class="fa fa-plus"></i>
							</span>
                            Добавить еще одно место
                        </a>
                        <div class="form-item row flex-column">
                            <label class="col-12 calc__label big">Откуда</label>
                            <div class="col-12">
                                <div class="form-item">
                                    <input type="text" class="form-control" placeholder="email@example.com">
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="radio1" name="radio-stacked" required>
                                    <label class="custom-control-label" for="radio1">Самостоятельно привезти груз  на терминал (100 <span class="rouble">p</span>)</label>
                                </div>
                                <div class="form-item ininner">
                                    <div class="relative">
                                        <i class="dropdown-toggle fa-icon"></i>
                                        <select class="custom-select">
                                            <option disabled selected>Выберите из списка</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                    <div class="form-group-unlink"><a href="#" class="link-with-dotted">Выбрать отделение на карте</a></div>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="radio2" name="radio-stacked" required>
                                    <label class="custom-control-label" for="radio2">Забрать груз от адреса отправителя (1 050 <span class="rouble">p</span>)</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-item row flex-column">
                            <label class="col-12 calc__label big">Куда</label>
                            <div class="col-12">
                                <div class="form-item">
                                    <input type="text" class="form-control" placeholder="email@example.com">
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="radio1" name="radio-stacked" required>
                                    <label class="custom-control-label" for="radio1">Самостоятельно забрать груз  на терминал (100 <span class="rouble">p</span>)</label>
                                </div>
                                <div class="form-item ininner">
                                    <div class="relative">
                                        <i class="dropdown-toggle fa-icon"></i>
                                        <select class="custom-select">
                                            <option disabled selected>Выберите из списка</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                    <div class="form-group-unlink"><a href="#" class="link-with-dotted">Выбрать отделение на карте</a></div>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="radio2" name="radio-stacked" required>
                                    <label class="custom-control-label" for="radio2">Доставить груз до адреса получателя (1 050 <span class="rouble">p</span>)</label>
                                </div>
                            </div>
                        </div>
                        <div class="calc__title">Дополнительные услуги</div>
                        <div class="form-item form-group-additional">
                            <div class="relative">
                                <i class="dropdown-toggle fa-icon"></i>
                                <select class="custom-select">
                                    <option disabled selected>Упаковка</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-item form-group-additional">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check1">
                                <label class="custom-control-label" for="check1">Страхование</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check2">
                                <label class="custom-control-label" for="check2">Погрузо-разгрузочные работы</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check3">
                                <label class="custom-control-label" for="check3">Доставка к точному времен</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check3">
                                <label class="custom-control-label" for="check3">Дата исполнение заказа</label>
                            </div>
                        </div>
                        <div class="form-item ininner">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="relative">
                                        <i class="fa-icon fa fa-calendar"></i>
                                        <input type="text" class="form-control" placeholder="12.01.2019">
                                    </div>
                                </div>
                                <span class="annotation-text col-6">дата приезда машины к отправителю</span>
                            </div>
                        </div>
                        <div class="form-item ininner">
                            <div class="row align-items-center">
                                <div class="input-group col-6">
                                    <input type="text" class="form-control text-center" placeholder="09:00" />
                                    <i class="group-input__icon fa fa-minus"></i>
                                    <input type="text" class="form-control text-center" placeholder="17:00" />
                                </div>
                                <span class="annotation-text col-6">время забора</span>
                            </div>
                        </div>
                        <div class="form-item d-flex flex-column flex-sm-row">
                            <button class="btn margin-item btn-danger">Оформить заказ</button>
                            <button class="btn margin-item btn-default">Сохранить черновик</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-lg-6 col-xl-4 offset-xl-2">
                    <section class="block__itogo">
                        <header class="block__itogo_title">Перевозка груза включает</header>
                        <div class="block__itogo_item d-flex">
                            <div class="d-flex flex-wrap">
                                <span class="block__itogo_label">Забор груза:</span>
                                <span class="block__itogo_value">Терминал</span>
                            </div>
                            <span class="block__itogo_price d-flex flex-nowrap">
								<span class="block__itogo_amount">155</span>
								<span class="rouble">p</span>
							</span>
                        </div>
                        <div class="block__itogo_item d-flex">
                            <div class="d-flex flex-wrap">
                                <span class="block__itogo_label">Межтерминальная перевозка:</span>
                                <span class="block__itogo_value">Москва – Екатеринбург</span>
                            </div>
                            <span class="block__itogo_price d-flex flex-nowrap">
								<span class="block__itogo_amount">155</span>
								<span class="rouble">p</span>
							</span>
                        </div>
                        <div class="block__itogo_item d-flex">
                            <div class="d-flex flex-wrap">
                                <span class="block__itogo_label">Доставка груза:</span>
                                <span class="block__itogo_value">Терминал</span>
                            </div>
                            <span class="block__itogo_price d-flex flex-nowrap">
								<span class="block__itogo_amount">155</span>
								<span class="rouble">p</span>
							</span>
                        </div>
                        <div class="block__itogo_item d-flex">
                            <div class="d-flex flex-wrap">
                                <span class="block__itogo_label">Дополнительные услуги:</span>
                                <span class="block__itogo_value">Погрузочно-разгрузочные работы</span>
                            </div>
                            <span class="block__itogo_price d-flex flex-nowrap">
								<span class="block__itogo_amount">155</span>
								<span class="rouble">p</span>
							</span>
                        </div>
                        <div class="separator-hr"></div>
                        <footer class="block__itogo_footer d-flex">
                            <span>Стоимость перевозки*</span>
                            <span class="block__itogo_price d-flex flex-nowrap">
								<span class="block__itogo_amount">12 850</span>
								<span class="rouble">p</span>
							</span>
                        </footer>
                    </section>
                    <div class="annotation-text">* - Предварительный расчет. Точная стоимость доставки будет определена после обмера груза специалистами компании БСД на складе.</div>
                </div>
            </div>
        </div>
    </section>
@endsection