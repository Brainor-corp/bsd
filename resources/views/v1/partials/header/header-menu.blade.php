@foreach($nodeTree as $node)
    @if(count($node->children) > 0)
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="{{ url($node->url) }}" role="button" aria-haspopup="true" aria-expanded="false">{{ $node->title }}</a>
            <div role="menu" class="dropdown-menu">
                @include('v1.partials.header.header-menu', ['nodeTree' => $node->children])
            </div>
        </li>
    @else
        <a class="dropdown-item" href="{{ url($node->url) }}">{{ $node->title }}</a>
    @endif
@endforeach