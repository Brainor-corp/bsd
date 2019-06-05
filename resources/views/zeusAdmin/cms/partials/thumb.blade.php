<span>Миниатюра</span>
<div id="thumb" class="mt-2" style="width: 200px">
    @if(!empty($cur_page["thumb"]))
        <img src="{{ $cur_page["thumb"] }}" class="img-fluid" alt="thumb">
    @else
            <span>Миниатюра не задана.</span>
    @endif

</div>