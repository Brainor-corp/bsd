@foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p>{{ Session::get('alert-' . $msg) }}</p>
                </div>
            </div>
        </div>
    @endif
@endforeach