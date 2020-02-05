@if(isset($order))
    <h4>Повторная отправка:</h4>
    <div class="alert resend-alert" style="display: none"></div>
    <button type="button" data-order-id="{{ $order->id }}" class="btn btn-success resend-button" id="resend-email">Повторный EMail-администратору</button>
    <button type="button" data-order-id="{{ $order->id }}" class="btn btn-success resend-button" id="resend-1c">Повторная отправка в 1c</button>
    <hr>
@endif
