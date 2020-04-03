<section class="services">
    <div class="container px-lg-5">
        <div class="row">
            <div class="col-12">
                <p style="text-align: center;"><span style="font-size:24px;"><strong>Дополнительные услуги</strong></span></p>
            </div>
        </div>
        <div class="row align-items-center justify-content-md-between justify-content-center my-5">
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/contract.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Оформление <br> документов</span>
                    @include(
                        'v1.pages.landings.default.partials.service_modal',
                        ['service' => $servicesPosts->where('slug', 'oformlenie-dokumentov')->first()]
                    )
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/shield.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Страхование <br> грузов</span>
                    @include(
                        'v1.pages.landings.default.partials.service_modal',
                        ['service' => $servicesPosts->where('slug', 'strahovanie-gruzov')->first()]
                    )
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/box.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Упаковка <br> грузов</span>
                    @include(
                        'v1.pages.landings.default.partials.service_modal',
                        ['service' => $servicesPosts->where('slug', 'upakovka-gruzov')->first()]
                    )
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/boxes.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Сборные <br> грузы</span>
                    @include(
                        'v1.pages.landings.default.partials.service_modal',
                        ['service' => $servicesPosts->where('slug', 'sbornye-gruzy')->first()]
                    )
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/movers.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Опытные <br> грузчики</span>
                    @include(
                        'v1.pages.landings.default.partials.service_modal',
                        ['service' => $servicesPosts->where('slug', 'opytnye-gruzchiki')->first()]
                    )
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/truck.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Срочная <br> доставка</span>
                    @include(
                        'v1.pages.landings.default.partials.service_modal',
                        ['service' => $servicesPosts->where('slug', 'srochnaya-dostavka')->first()]
                    )
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/marker.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Отслеживание <br> груза</span>
                    @include(
                        'v1.pages.landings.default.partials.service_modal',
                        ['service' => $servicesPosts->where('slug', 'otslezhivanie-gruza')->first()]
                    )
                </div>
            </div>
        </div>
    </div>
</section>
