$(document).ready(function () {
    $('#input_rate_id').change(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/route-tariffs-options',
            data: {rate_id: $('#input_rate_id').val()},
            cache: false,
            success: function(html){
                $('#input_threshold_id').html(html);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
});