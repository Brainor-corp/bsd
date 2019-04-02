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
            url: '/api/calculator/get-destination-cities',
            data: {ship_city:value},//здесь мы передаем стандартным пост методом без сериализации. В конечном скрипте данные будут лежать в $_POST['ajax_data']
            cache: false,
            beforeSend: function() {

            },
            success: function(html){
                $('#dest_city').selectize()[0].selectize.destroy();
                $('#dest_city').html(html);
                $('#dest_city').selectize({
                    onChange: function(value) {// при изменении города назначения
                        getRoute();
                    }
                });
                getRoute();
            }
        });
    }
});

$('#dest_city').selectize({
    onChange: function(value) {// при изменении города назначения
        getRoute();
    }
});

//При изменении параметров пакета
$('.package-params').on('change', function () {
    let shipCityID = $("#ship_city").val(),
        destCityID = $("#dest_city").val();
    if (shipCityID && destCityID) {
        getBaseTariff();
    }
});

// получение маршрута
var getRoute = function () {
    let shipCityID = $("#ship_city").val(),
        destCityID = $("#dest_city").val(),
        formData  = $('.calculator-form').serialize();
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
                $('#delivery-time').text(data.delivery_time);
                getBaseTariff();
            }
        });
    } else
        $('#delivery_time').removeClass('loading').val('');

};

var getBaseTariff = function () {
    let shipCityID = $("#ship_city").val(),
        destCityID = $("#dest_city").val(),
        formData  = $('.calculator-form').serialize();
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
                $('#base-price').html(data.base_price);
                $('#base-price').attr('data-base-price', data.base_price);
                $('#total-price').html(data.total_data.total);
                $('#total-price').attr('data-total-price', data.total_data.total);
                $('#total-volume').attr('data-total-volume', data.total_volume);
            }
        });

};

var getTotalPrice = function () {
    let shipCityID = $("#ship_city").val(),
        destCityID = $("#dest_city").val(),
        basePrice = $("#base-price").data('basePrice'),
        totalVolume = $("#total-volume").data('totalVolume'),
        formData  = $('.calculator-form').serialize();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/api/calculator/get-total-price',
        data: {ship_city:shipCityID, dest_city:destCityID, base_price:basePrice, total_volume:totalVolume, formData},
        cache: false,
        beforeSend: function() {

        },
        success: function(data){
            console.log(data);
            $('#total-price').html(data.total);
            $('#total-price').attr('data-total-price', data.total);
        }
    });

};

$(document).on('change', '.custom-service-checkbox', function (e) {
    e.preventDefault();
    getTotalPrice();
});

$(document).on('change', '#discount', function (e) {
    e.preventDefault();
    getTotalPrice();
});

$(document).on('change', '#insurance', function (e) {
    $('#insurance-amount').attr('value', '');
    $('#insurance-amount').val('');
    $('#insurance-amount-wrapper').toggle();
});

$(document).on('change', '#insurance-amount', function (e) {
    e.preventDefault();
    getTotalPrice();
});

$(document).on('change', '.package-dimensions', function (e) {
    e.preventDefault();

    let id = $(this).data('packageId'),
        length = $('#packages_'+ id +'_length').val(),
        width = $('#packages_'+ id +'_width').val(),
        height = $('#packages_'+ id +'_height').val(),
        volume = 1
    ;
    if(length === ''){
        length = 0.1;
        $('#packages_'+ id +'_length').attr('value', length).val(length);
    }else{length = parseFloat(length.replace(',', '.'))}
    if(width === ''){
        width = 0.1;
        $('#packages_'+ id +'_width').attr('value', width).val(width);
    }else{width = parseFloat(width.replace(',', '.'))}
    if(height === ''){
        height = 0.1;
        $('#packages_'+ id +'_height').attr('value', height).val(height);
    }else{height = parseFloat(height.replace(',', '.'))}

    volume = parseFloat((length * width * height).toFixed(2));

    $('#packages_'+ id +'_volume').attr('value', volume).val(volume);
});

$(document).on('change', '.package-weight', function (e) {
    e.preventDefault();
});

$(document).on('change', '.package-volume', function (e) {
    e.preventDefault();

    let id = $(this).data('packageId'),
        length = $('#packages_'+ id +'_length').val(),
        width = $('#packages_'+ id +'_width').val(),
        height = $('#packages_'+ id +'_height').val(),
        volume = $('#packages_'+ id +'_volume').val()
    ;
    if(volume !== ''){
        if(length !== '' && width !== '' && height !== ''){
            height = volume/(length*width);
            $('#packages_'+ id +'_height').attr('value', height).val(height);
        }
        if(length !== '' && width !== '' && height === ''){
            width = volume/length;
            $('#packages_'+ id +'_width').attr('value', width).val(width);
        }
    }
});