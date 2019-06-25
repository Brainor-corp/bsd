@foreach($nodeTree as $node)
    @if(count($node->children) > 0)
        <div class="col-12 col-sm-6 col-lg-3 col-xl footer-item">
            <div class="footer_title">{{ $node->title }}</div>
            <ul class="m-0">
                @include('v1.partials.footer.footer-menu', ['nodeTree' => $node->children])
            </ul>
        </div>
    @else
        <li class="ftr_list-item"><a class="ftr_link" href="{{ url($node->url) }}">{{ $node->title }}</a></li>
    @endif
@endforeach