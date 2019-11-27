@foreach($nodeTree as $node)
    @if(!isset($depth))
        @php($depth = 0)
    @endif
    @if(null == $node->parent_id)
        @php($depth = 0)
    @endif
    @if(count($node->children) > 0)
        @php($depth++)
        @if($depth < 2)
            <li class="dropdown list-inline-item d-md-inline-block d-block">
                <a class="dropdown-toggle" data-toggle="dropdown" href="{{ url($node->url) }}" role="button" aria-haspopup="true" aria-expanded="false">{{ $node->title }}</a>
                <div role="menu" class="dropdown-menu">
                    @include('v1.partials.header.header-menu', ['nodeTree' => $node->children, 'depth' => $depth])
                </div>
            </li>
        @endif
    @else
        <a class="dropdown-item" href="{{ url($node->url) }}">{{ $node->title }}</a>
    @endif
@endforeach