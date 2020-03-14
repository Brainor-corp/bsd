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
                <td>{{ isset($data['order_number']) && !empty(trim($data['order_number'])) ? $data['order_number'] : '-' }}</td>
                <td>{{ isset($data['forwarding_receipt_number']) && !empty(trim($data['forwarding_receipt_number'])) ? $data['forwarding_receipt_number'] : '-' }}</td>
                <td>
                    {{ isset($data['delivery_date']) && !empty(trim($data['delivery_date'])) ? $data['delivery_date'] : '-' }}
                    <br><span class="annotation-text">Плановая дата доставки</span>
                </td>
                <td>{{ isset($data['order_status']) && !empty(trim($data['order_status'])) ? $data['order_status'] : '-' }}</td>
                <td>{{ isset($data['cargo_status']) && !empty(trim($data['cargo_status'])) ? $data['cargo_status'] : '-' }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@else
    <p>Информация отсутствует.</p>
@endif
