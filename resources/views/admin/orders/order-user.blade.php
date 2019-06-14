<div class="form-group">
    <label for="">Пользователь</label>
    <br>
    @if(isset($order->user))
        <a href="{{ route('zeusAdmin.section.edit.form', ['section' => 'users', 'id' => $order->user->id]) }}">
            {{ $order->user->full_name }}
        </a>
    @else
        <span>Не указан</span>
    @endif
</div>