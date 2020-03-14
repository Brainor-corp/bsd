$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/shipment-search/ajax',
        data: {
          'type': $('input[name="type"]').val(),
          'number': $('input[name="number"]').val()
        },
        cache: false,
        success: function(html) {
            $('.result-wrapper').html(html);
        },
        error: function () {
            $('.result-wrapper').html('<p>Информация отсутствует.</p>')
        }
    });
});
