@if(isset($order))
    <h4>Повторная отправка:</h4>
    <div class="alert resend-alert" style="display: none"></div>
    <div class="row align-content-center">
        <div class="col-auto">
            <button type="button" data-order-id="{{ $order->id }}" class="btn btn-success resend-button" id="resend-email">Повторный EMail-администратору</button>
        </div>
        <div class="col-auto">
            <button type="button" data-order-id="{{ $order->id }}" class="btn btn-success resend-button" id="resend-1c">Повторная отправка в 1c</button>
        </div>
        <div class="col-auto">
            <div class="input-group">
                <input type="email" id="email" class="form-control" placeholder="name@domain.com">
                <div class="input-group-append">
                    <button type="button"
                            data-order-id="{{ $order->id }}"
                            class="btn btn-success resend-button"
                            id="resend-this-email"
                    >
                        Отправить по эл. почте
                    </button>
                </div>
            </div>
        </div>
    </div>
    <hr>
@endif
