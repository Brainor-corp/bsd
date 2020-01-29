<ul>
    @forelse($documents as $document)
        <li>
            <a class="btn-link document-link text-info" href="{{ route('download-document', [
                'document_id_1c' => $document['id'],
                'document_type_id_1c' => $document['type'],
                'document_name' => json_encode($document['name'])
            ]) }}">{{ $document['name'] }}</a>
            @if(isset($order->payment_status) && $order->payment_status->name === 'Не оплачена' && $document['type'] === 5)
                <a href="{{ route('make-payment', [
                    'document_id' => $document['id']
                ]) }}" class="text-danger">Оплатить онлайн</a>
            @endif
        </li>
    @empty
        <li>
            <span>В данный момент документы по этой заявке недоступны.</span>
        </li>
    @endforelse
</ul>
