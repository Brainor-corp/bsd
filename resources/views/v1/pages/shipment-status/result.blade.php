@if(isset($data))
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№ Заявки</th>
                <th>№ ЭР</th>
                <th>Дата доставки</th>
                <th>Статус заявки</th>
                <th>Статус получения груза</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $data['order_number'] ?? '-' }}</td>
                <td>{{ $data['forwarding_receipt_number'] ?? '-' }}</td>
                <td>
                    {{ $data['delivery_date'] ?? 'Уточняется' }}
                    <br><span class="annotation-text">Плановая дата доставки</span>
                </td>
                <td>{{ $data['order_status'] ?? '-' }}</td>
                <td>{{ $data['cargo_status'] ?? '-' }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@else
    <p>Информация отсутствует.</p>
@endif
