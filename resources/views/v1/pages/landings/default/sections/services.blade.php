<section class="services">
    <div class="container px-lg-5">
        <div class="row">
            <div class="col-12">
                <p style="text-align: center;"><span style="font-size:24px;"><strong>Дополнительные услуги</strong></span></p>
            </div>
        </div>
        <div class="row align-items-center justify-content-xl-between justify-content-center my-5">
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/contract.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Доставка <br>документов</span>
                    <a href="{{ url('/uslugi/dostavka-dokumentov') }}" class="stretched-link"></a>
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/shield.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Страхование <br> грузов</span>
                    <a href="{{ url('/klientam/dopolnitelnye-uslugi#insurance') }}" class="stretched-link"></a>
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/box.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Упаковка <br> грузов</span>
                    <a href="{{ url('/klientam/dopolnitelnye-uslugi#packaging') }}" class="stretched-link"></a>
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/boxes.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Адресная доставка <br> и забор груза</span>
                    <a href="{{ url('/uslugi/adresnaya-dostavka-i-zabor-gruza') }}" class="stretched-link"></a>
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/movers.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Адресная <br> доставка груза</span>
                    <a href="{{ url('/uslugi/adresnaya-dostavka-i-zabor-gruza') }}" class="stretched-link"></a>
                </div>
            </div>
            <div class="col-md-auto col-6 text-center mb-3">
                <div class="service-block">
                    <div class="round position-relative d-inline-block">
                        <img src="{{ asset('images/landing_icons/truck.png') }}" alt="" class="img-fluid">
                    </div>
                    <br>
                    <span class="font-weight-bold">Доставка в <br> гипермаркеты</span>
                    <a href="{{ url('/uslugi/dostavka-v-gipermarkety') }}" class="stretched-link"></a>
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
