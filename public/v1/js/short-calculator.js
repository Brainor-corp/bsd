﻿$(document).ready(function () {
    let swapCityTo = null;
    let swapCityFrom = null;

    $('#short_ship_city').selectize({
        openOnFocus:false,
        onInitialize: function () {
            var that = this;

            this.$control.on("keyup", function (event) {
                if(event.target.value.length > 2){
                    that.open();
                }else{
                    that.close();
                }
            });

            this.$control.on("click", function (event) {
                $('#short_ship_city').selectize()[0].selectize.clear()
                $('#short_dest_city').selectize()[0].selectize.clear()
            });
        },
        onChange: function(value) {// при изменении города отправления

            if (!value.length) return;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/api/calculator/get-destination-cities',
                data: {
                    ship_city: value,
                    pointsNeed: true
                },//здесь мы передаем стандартным пост методом без сериализации. В конечном скрипте данные будут лежать в $_POST['ajax_data']
                cache: false,
                beforeSend: function() {
                    $('.short-calc-preload').fadeIn(100);
                },
                success: function(html){
                    $('#short_dest_city').selectize()[0].selectize.destroy();
                    $('#short_dest_city').html(html);
                    $('#short_dest_city').selectize({
                        openOnFocus:false,
                        onInitialize: function () {
                            var that = this;

                            this.$control.on("keyup", function (event) {
                                if(event.target.value.length > 2){
                                    that.open();
                                }else{
                                    that.close();
                                }
                            });

                            if(swapCityTo !== null) {
                                $('#short_dest_city').selectize()[0].selectize.setValue(swapCityFrom);
                                swapCityTo = null;
                            }

                            this.$control.on("click", function (event) {
                                $('#short_dest_city').selectize()[0].selectize.clear()
                            });
                        },
                        onChange: function(value) {// при изменении города назначения
                            getShortBaseTariff();
                        }
                    });

                    $('.short-calc-preload').fadeOut(100);

                    // getShortBaseTariff();
                },
                error: function (data) {
                    $('.short-calc-preload').fadeOut(100);
                }
            });
        }
    });

    $('#short_dest_city').selectize({
        openOnFocus:false,
        onInitialize: function () {
            var that = this;

            this.$control.on("keyup", function (event) {
                if(event.target.value.length > 2){
                    that.open();
                }else{
                    that.close();
                }
            });

            this.$control.on("click", function (event) {
                $('#short_dest_city').selectize()[0].selectize.clear()
            });
        },
        onChange: function(value) {// при изменении города назначения

            // var $select = $('#dest_city').selectize();
            // var selectize = $select[0].selectize;
            // selectize.setValue(value, false);

            getShortBaseTariff();
        }
    });

    var getShortBaseTariff = function () {
        let shipCityID = $("#short_ship_city").val(),
            destCityID = $("#short_dest_city").val(),
            formData  = $('.short_calculator-form').serialize(),
            weight = $(".short_package-weight").val(),
            volume = $(".short_package-volume").val(),
            totalPrice = 0;

        // Проверяем, выбраны ли города отправления и назначения
        if(shipCityID !== "" && destCityID !== "") {
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
                    $('.short-calc-preload').fadeIn(100);
                },
                success: function(data){
                    if(data.reverse_route_exists) {
                        $('.swap-button').removeAttr('disabled');
                        $('.swap-button').fadeIn();
                    } else {
                        $('.swap-button').attr('disabled', 'disabled');
                        $('.swap-button').fadeOut();
                    }

                    totalPrice = data.total_data.total;
                    $('#short_base-price').html(data.base_price);
                    $('#short_base-price').attr('data-base-price', data.base_price);
                    $('#short_ins').html(data.total_data.insurance);
                    $('#short_ins').attr('data-base-price', data.total_data.insurance);
                    $('#short_total-price').html(totalPrice);
                    $('#short_total-price').attr('data-total-price', totalPrice);
                    $('#short_total-volume').attr('data-total-volume', data.total_volume);

                    $('#base-price').html(data.base_price);
                    $('#base-price').attr('data-base-price', data.base_price);
                    $('#total-price').html(totalPrice);
                    $('#total-price').attr('data-total-price', totalPrice);
                    $('#total-volume').attr('data-total-volume', data.total_volume);

                    $('#deliveryPriceBlock').hide();
                    $('input[name="deliveryName"]').val(null);
                    if(data.delivery_to_point !== null) {
                        $('#deliveryDistance').html(data.delivery_to_point.distance + 'км');
                        $('#deliveryPrice').html(data.delivery_to_point.price);
                        $('#deliveryPriceBlock').show();
                    }

                    $('#bringPriceBlock').hide();
                    $('input[name="bringName"]').val(null);
                    if(data.bring_to_point !== null) {
                        $('#bringDistance').html(data.bring_to_point.distance + 'км');
                        $('#bringPrice').html(data.bring_to_point.price);
                        $('#bringPriceBlock').show();
                    }

                    $('.short-calc-preload').fadeOut(100);
                },
                error: function (err) {
                    $('.short-calc-preload').fadeOut(100);
                }
            });
        }
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

    $(document).on('click', '.swap-button', (e) => {
        let cityFromSelect = $('#short_ship_city').selectize()[0].selectize;
        let cityToSelect = $('#short_dest_city').selectize()[0].selectize;

        swapCityFrom = cityFromSelect.getValue();
        swapCityTo = cityToSelect.getValue();

        cityToSelect.setValue(swapCityFrom, true);
        cityFromSelect.setValue(swapCityTo);
    });

    getShortBaseTariff();
});
