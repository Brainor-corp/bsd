$(document).ready(function () {
    $('.events-feed__close').click(function () {
        let btn = $(this);
        let id = btn.data('event-id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/event-hide',
            data: {event_id: id},
            cache: false,
            success: function(response){
                if(response === 'ok'){
                    $(btn).parent().hide('slow');
                    $(btn).parent().remove();
                }
            },
            error: function (err) {
                // console.log(err);
            }
        });
    });
});