@forelse($documents as $document)
    <li>
        <a href="{{ route('download-document-receipt', [
            'order_id' => $orderId,
            'id' => $document['id'],
            'type' => $document['type'],
        ]) }}" class="btn-link document-link text-info">{{ $document['name'] }}</a>
    </li>
@empty
    <li>Доступные документы отсутствуют</li>
@endforelse