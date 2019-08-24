$(document).ready(function () {
    $('#ship_city').selectize({
        onChange: function(value) {// при изменении города отправления
            if (!value.length) return;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/api/prices/get-destination-cities',
                data: {ship_city:value},
                cache: false,
                beforeSend: function() {

                },
                success: function(html){
                    $('#dest_city').selectize()[0].selectize.destroy();
                    $('#dest_city').html(html);
                    $('#dest_city').selectize({});
                }
            });
        }
    });

    $('#dest_city').selectize({});
});