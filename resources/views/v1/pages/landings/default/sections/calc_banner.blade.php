<section>
    <div class="container-fluid bg-dart-gray">
        <div class="container px-lg-5">
            <div class="row align-items-center justify-content-xl-between justify-content-center py-5">
                <div class="col-auto mb-xl-0 mb-4">
                    <div class="row">
                        <div class="col-auto">
                            <img src="{{ asset('/images/landing_icons/time.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="col text-white">
                            <p class="mb-0 h5">От <strong>{{ $route->delivery_time }}</strong> {{ \App\Http\Helpers\TextHelper::daysTitleByCount($route->delivery_time) }}</p>
                            <p class="h6">Срок доставки</p>
                        </div>
                    </div>
                </div>
                <div class="col-auto mb-xl-0 mb-4">
                    <div class="row">
                        <div class="col-auto">
                            <img src="{{ asset('/images/landing_icons/wallet.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="col text-white">
                            <p class="mb-0 h5">От <strong>{{ $route->min_cost }}</strong> руб.</p>
                            <p class="h6">Стоимость доставки</p>
                        </div>
                    </div>
                </div>
                <div class="col-auto mb-xl-0 mb-4">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger px-md-5" data-toggle="modal" data-target="#contactModal">
                        Получить детальный просчет
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('landing-send-mail') }}" method="post" id="calcUserForm">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="contactModalLabel">Получить детальный просчет</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="phone">Ваш телефон</label>
                                            <input type="text" class="form-control" name="phone" id="phone" aria-describedby="phoneHelp" placeholder="Введите телефон" required>
                                            <span class="invalid-feedback error phone" role="alert" style="display: none"></span>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="check" required>
                                            <label class="form-check-label" for="check">
                                                Даю согласие на обработку <a href="/politika-konfidencialnosti">персональных данных</a>.
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <div class="g-recaptcha my-3" data-sitekey="{{ env('V2_GOOGLE_CAPTCHA_KEY') }}"></div>
                                            <span class="invalid-feedback error g-recaptcha-response" role="alert" style="display: none"></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Отправить</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
