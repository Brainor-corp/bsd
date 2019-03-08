@extends('v1.layouts.innerPageLayout')


@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="##" class="">Услуги</a></span>
            <span class="breadcrumb__item">Страхование</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Расчет стоимости</h1>
                    </header>
                    <div class="row">
                        <div class="col-md-6">
                            <form>
                                <div class="calc__title">Груз</div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Наименование груза*</label>
                                    <div class="col"><input type="text" class="form-control" placeholder="email@example.com"></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Тип груза*</label>
                                    <div class="col">
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
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8">
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
                                    <div class="col-4">
                                        <p class="calc__info">Габариты груза влияют на расчет стоимости, без их указания стоимость может быть неточной</p>
                                    </div>
                                </div>
                                <a href="##" class="add_anotherplace">
                                    <span class="badge calc_badge">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                    Добавить еще одно место
                                </a>
                                <div class="form-item row">
                                    <label class="col-auto calc__label big">Откуда</label>
                                    <div class="col">
                                        <div class="form-item">
                                            <input type="text" class="form-control" placeholder="email@example.com">
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="bring-your-own" name="from" required>
                                            <label class="custom-control-label" for="bring-your-own">Самостоятельно привезти груз  на терминал (100 <span class="rouble">p</span>)</label>
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
                                            <input type="radio" class="custom-control-input" id="pick-up-cargo" name="from" required>
                                            <label class="custom-control-label" for="pick-up-cargo">Забрать груз от адреса отправителя (1 050 <span class="rouble">p</span>)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-item row">
                                    <label class="col-auto calc__label big">Куда</label>
                                    <div class="col">
                                        <div class="form-item">
                                            <input type="text" class="form-control" placeholder="email@example.com">
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="you-can-pick" name="where" required>
                                            <label class="custom-control-label" for="you-can-pick">Самостоятельно забрать груз  на терминал (100 <span class="rouble">p</span>)</label>
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
                                            <input type="radio" class="custom-control-input" id="deliver-cargo" name="where" required>
                                            <label class="custom-control-label" for="deliver-cargo">Доставить груз до адреса получателя (1 050 <span class="rouble">p</span>)</label>
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
                                        <input type="checkbox" class="custom-control-input" id="insurance">
                                        <label class="custom-control-label" for="insurance">Страхование</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="loading-and-unloading">
                                        <label class="custom-control-label" for="loading-and-unloading">Погрузо-разгрузочные работы</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="exact-time">
                                        <label class="custom-control-label" for="exact-time">Доставка к точному времен</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="date-execution">
                                        <label class="custom-control-label" for="date-execution">Дата исполнение заказа</label>
                                    </div>
                                </div>
                                <div class="form-item ininner">
                                    <div class="row align-items-center">
                                        <div class="col-4">
                                            <div class="relative">
                                                <i class="fa-icon fa fa-calendar"></i>
                                                <input type="text" class="form-control" placeholder="12.01.2019">
                                            </div>
                                        </div>
                                        <span class="annotation-text">дата приезда машины к отправителю</span>
                                    </div>
                                </div>
                                <div class="form-item ininner">
                                    <div class="row align-items-center">
                                        <div class="input-group col-4">
                                            <input type="text" class="form-control text-center" placeholder="09:00" />
                                            <i class="group-input__icon fa fa-minus"></i>
                                            <input type="text" class="form-control text-center" placeholder="17:00" />
                                        </div>
                                        <span class="annotation-text">время забора</span>
                                    </div>
                                </div>
                                <div class="calc__title">Отправитель</div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="sender-legally" name="sender" required />
                                    <label class="custom-control-label" for="sender-legally">Юридическое лицо</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="sender-private" name="sender" required />
                                    <label class="custom-control-label" for="sender-private">Физическое лицо</label>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Правовая форма*</label>
                                    <div class="col calc__inpgrp"><input type="text" class="form-control" placeholder="ИП, ООО, АО" required /></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Название организации*</label>
                                    <div class="col"><input type="text" class="form-control" required /></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Юридический адрес*</label>
                                    <div class="col">
                                        <input type="text" class="form-control form-item"  placeholder="Город" required/>
                                        <input type="text" class="form-control form-item"  placeholder="Улица" required/>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center form-item" placeholder="Дом" />
                                            <input type="text" class="form-control text-center form-item" placeholder="Корп." />
                                            <input type="text" class="form-control text-center form-item" placeholder="Стр." />
                                            <input type="text" class="form-control text-center" placeholder="Кв./оф." />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">ИНН*</label>
                                    <div class="col calc__inpgrp"><input type="text" class="form-control" /></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">КПП*</label>
                                    <div class="col calc__inpgrp"><input type="text" class="form-control" /></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Контактная лицо*</label>
                                    <div class="col calc__inpgrp"><input type="text" class="form-control" /></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Телефон*</label>
                                    <div class="col calc__inpgrp"><input type="text" class="form-control" /></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Дополнительная информация</label>
                                    <div class="col"><input type="text" class="form-control" /></div>
                                </div>
                                <div class="calc__title">Получатель</div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="recipient-legally" name="recipient" required>
                                    <label class="custom-control-label" for="recipient-legally">Юридическое лицо</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="recipient-private" name="recipient" required>
                                    <label class="custom-control-label" for="recipient-private">Физическое лицо</label>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Страна*</label>
                                    <div class="col">
                                        <div class="relative">
                                            <i class="dropdown-toggle fa-icon"></i>
                                            <select class="custom-select">
                                                <option disabled selected>Выберите из списка</option>
                                                <option>Россия</option>
                                                <option>Казахстан</option>
                                                <option>Грузия</option>
                                                <option>Индия</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">ФИО</label>
                                    <div class="col"><input type="text" class="form-control" required /></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Паспорт*</label>
                                    <div class="col calc__inpgrp">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center form-item" placeholder="Серия" required />
                                            <input type="text" class="form-control text-center form-item" placeholder="Номер" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Контактное лицо*</label>
                                    <div class="col calc__inpgrp"><input type="text" class="form-control" required/></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Телефон*</label>
                                    <div class="col calc__inpgrp"><input type="text" class="form-control" required/></div>
                                </div>
                                <div class="form-item row align-items-center">
                                    <label class="col-auto calc__label">Дополнительная информация</label>
                                    <div class="col"><input type="text" class="form-control" /></div>
                                </div>
                                <div class="calc__title">Данные плательщика</div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="sender" name="payer" required />
                                    <label class="custom-control-label" for="sender">Отправитель</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="recipient" name="payer" required />
                                    <label class="custom-control-label" for="recipient">Получатель</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="3rd-person" name="payer" required />
                                    <label class="custom-control-label" for="3rd-person">3-е лицо</label>
                                </div>
                                <div class="calc__title">Форма оплаты</div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="available" name="payment" required />
                                    <label class="custom-control-label" for="available">Наличный расчет</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="non-cash" name="payment" required />
                                    <label class="custom-control-label" for="non-cash">Безналичный расчет</label>
                                </div>
                                <div class="form-item d-flex">
                                    <button class="btn margin-item btn-danger">Оформить заказ</button>
                                    <button class="btn margin-item btn-default">Сохранить черновик</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 offset-md-2">
                            <section class="block__itogo">
                                <div class="block__itogo-inner">
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
                                </div>
                                <div class="annotation-text">* - Предварительный расчет. Точная стоимость доставки будет определена после обмера груза специалистами компании БСД на складе.</div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection