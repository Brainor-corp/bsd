@if(
    \Illuminate\Support\Facades\Auth::check() &&
    session()->has('process_order') &&
    session()->has('process_order_modal')
    // todo Добавить проверку подтверждённости пользователя
)
    @php
        $order = json_decode(session()->get('process_order'));
        session()->forget('process_order_modal');
    @endphp
    <div class="modal" id="process-order" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Незавершенный заказ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="relative">
                                <p>Вы не завершили один из своих заказов.</p>
                                <p>Хотите сделать это сейчас?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('calculator-show', [
                        'id' => $order->order_id ?? null,
                        'continue' => 1
                    ]) }}" class="btn margin-item btn-danger">К заказу</a>
                    <button type="button" class="btn margin-item btn-default" data-dismiss="modal" aria-label="Close">Отмена</button>
                </div>
            </div>
        </div>
    </div>
@endif