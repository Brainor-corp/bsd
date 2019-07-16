﻿$('#short_ship_city').selectize({
    onChange: function(value) {// при изменении города отправления

        if (!value.length) return;

        // var $select = $('#ship_city').selectize();
        // var selectize = $select.selectize;
        // selectize.setValue(value, false);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/api/calculator/get-destination-cities',
            data: {ship_city:value},//здесь мы передаем стандартным пост методом без сериализации. В конечном скрипте данные будут лежать в $_POST['ajax_data']
            cache: false,
            beforeSend: function() {

            },
            success: function(html){
                $('#short_dest_city').selectize()[0].selectize.destroy();
                $('#short_dest_city').html(html);
                $('#short_dest_city').selectize({
                    onChange: function(value) {// при изменении города назначения
                        // var $select = $('#dest_city').selectize();
                        // var selectize = $select[0].selectize;
                        // selectize.setValue(value, false);
                        getShortRoute();
                    }
                });

                // $('#dest_city').selectize()[0].selectize.destroy();
                // $('#dest_city').html(html);
                // $('#dest_city').selectize({
                //     onChange: function(value) {// при изменении города назначения
                //         getShortRoute();
                //     }
                // });

                getShortRoute();
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
});

$('#short_dest_city').selectize({
    onChange: function(value) {// при изменении города назначения

        // var $select = $('#dest_city').selectize();
        // var selectize = $select[0].selectize;
        // selectize.setValue(value, false);

        getShortRoute();
    }
});

// получение маршрута
var getShortRoute = function () {
    let shipCityID = $("#short_ship_city").val(),
        destCityID = $("#short_dest_city").val(),
        formData  = $('.short_calculator-form').serialize();
    if (shipCityID && destCityID) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/api/calculator/get-route',
            data: {ship_city:shipCityID, dest_city:destCityID, formData},
            cache: false,
            beforeSend: function() {

            },
            success: function(data){
                getShortBaseTariff();
            },
            error: function (err) {
                console.log(err);
            }
        });
    }

};

var getShortBaseTariff = function () {
    let shipCityID = $("#short_ship_city").val(),
        destCityID = $("#short_dest_city").val(),
        formData  = $('.short_calculator-form').serialize();

    // console.log(shipCityID);
    // console.log(destCityID);
    // console.log(formData);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/api/calculator/get-tariff',
            data: {ship_city:shipCityID, dest_city:destCityID, formData},
            cache: false,
            beforeSend: function() {

            },
            success: function(data){
                $('#short_base-price').html(data.base_price);
                $('#short_base-price').attr('data-base-price', data.base_price);
                $('#short_total-price').html(data.total_data.total);
                $('#short_total-price').attr('data-total-price', data.total_data.total);
                $('#short_total-volume').attr('data-total-volume', data.total_volume);

                $('#base-price').html(data.base_price);
                $('#base-price').attr('data-base-price', data.base_price);
                $('#total-price').html(data.total_data.total);
                $('#total-price').attr('data-total-price', data.total_data.total);
                $('#total-volume').attr('data-total-volume', data.total_volume);
            },
            error: function (err) {
                console.log(err);
            }
        });

};

$(document).on('change', '.short_package-weight', function (e) {
    e.preventDefault();

    $('.package-weight').val($(this).val());
    $('.package-weight').attr('value' , $(this).val());

    getShortBaseTariff();
});

$(document).on('change', '.short_package-volume', function (e) {
    e.preventDefault();

    $('.package-volume').val($(this).val());
    $('.package-volume').attr('value' , $(this).val());

    getShortBaseTariff();
});