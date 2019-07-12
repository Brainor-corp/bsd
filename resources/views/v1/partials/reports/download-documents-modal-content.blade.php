<ul>
    @forelse($documents as $document)
        <li>
            <a class="btn-link document-link text-info" href="{{ route('download-document', [
                'document_id_1c' => $document['id'],
                'document_type_id_1c' => $document['type'],
            ]) }}">{{ $document['name'] }}</a>
        </li>
    @empty
        <li>
            <span>В данный момент документы по этому заказу недоступны.</span>
        </li>
    @endforelse
</ul>