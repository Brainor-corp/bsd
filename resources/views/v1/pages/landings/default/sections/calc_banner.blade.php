<section>
    <div class="container-fluid bg-dart-gray">
        <div class="container px-5">
            <div class="row align-items-center justify-content-between py-5">
                <div class="col-md-auto col-12">
                    <div class="row">
                        <div class="col-auto">
                            <img src="{{ asset('/images/landing_icons/time.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="col text-white">
                            <p class="mb-0">От <strong>{{ $route->delivery_time }}</strong> {{ \App\Http\Helpers\TextHelper::daysTitleByCount($route->delivery_time) }}</p>
                            <p>Срок доставки</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-auto col-12">
                    <div class="row">
                        <div class="col-auto">
                            <img src="{{ asset('/images/landing_icons/wallet.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="col text-white">
                            <p class="mb-0">От <strong>{{ $route->min_cost }}</strong> руб.</p>
                            <p>Стоимость доставки</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-auto col-12">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger px-md-5" data-toggle="modal" data-target="#contactModal">
                        Получить детальный просчет
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="contactModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    ...
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
