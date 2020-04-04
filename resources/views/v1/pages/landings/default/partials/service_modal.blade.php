@if(isset($service) && !empty($service->content))
    <a href="#" class="stretched-link" data-toggle="modal" data-target="{{ "#$service->slug-modal" }}"></a>

    <!-- Modal -->
    <div class="modal fade" id="{{ "$service->slug-modal" }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $service->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    {!! $service->content !!}
                </div>
            </div>
        </div>
    </div>
@endif
