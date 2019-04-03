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
        totalVolume = $("#total-volume").attr('data-total-volume'),
        formData  = $('.calculator-form').serialize();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    console.log(totalVolume);
    console.log(basePrice);
    console.log(formData);
    $.ajax({
        type: 'post',
        url: '/api/calculator/get-total-price',
        data: {ship_city:shipCityID, dest_city:destCityID, base_price:basePrice, total_volume:totalVolume, formData},
        cache: false,
        beforeSend: function() {

        },
        success: function(data){
            $('#custom-services-total-wrapper').css({
                'display': 'none',
            });
            let services = '';

            console.log(data);

            if(typeof data.services !== 'undefined') {
                if (Object.keys(data.services).length > 0) {

                    $.each(data.services, function(index, item) {
                        services = services +
                            '<div class="custom-service-total-item">'+
                                '<div class="block__itogo_item d-flex">'+
                                    '<div class="d-flex flex-wrap" id="services-total-names">'+
                                        '<span class="block__itogo_value">' + item.name + '</span>'+
                                    '</div>'+
                                    '<span class="block__itogo_price d-flex flex-nowrap"  id="services-total-prices">'+
                                        '<span class="block__itogo_amount">' + item.total + '</span>'+
                                        '<span class="rouble">p</span>'+
                                    '</span>'+
                                '</div>'+
                            '</div>';
                    });
                }
            }
            if(typeof data.insurance !== 'undefined') {
                services = services +
                    '<div class="custom-service-total-item">'+
                    '<div class="block__itogo_item d-flex">'+
                    '<div class="d-flex flex-wrap" id="services-total-names">'+
                    '<span class="block__itogo_value">Страхование</span>'+
                    '</div>'+
                    '<span class="block__itogo_price d-flex flex-nowrap"  id="services-total-prices">'+
                    '<span class="block__itogo_amount">' + data.insurance + '</span>'+
                    '<span class="rouble">p</span>'+
                    '</span>'+
                    '</div>'+
                    '</div>';
            }
            if(typeof data.discount !== 'undefined') {
                services = services +
                    '<div class="custom-service-total-item">'+
                    '<div class="block__itogo_item d-flex">'+
                    '<div class="d-flex flex-wrap" id="services-total-names">'+
                    '<span class="block__itogo_value">Скидка</span>'+
                    '</div>'+
                    '<span class="block__itogo_price d-flex flex-nowrap"  id="services-total-prices">'+
                    '<span class="block__itogo_amount">' + data.discount + '</span>'+
                    '<span class="rouble">p</span>'+
                    '</span>'+
                    '</div>'+
                    '</div>';
            }

            if(services !== ''){
                $('#custom-services-total-list').html(services);

                $('#custom-services-total-wrapper').css({
                    'display': 'block',
                });
            }

            $('#base-price').html(data.total);
            $('#base-price').attr('data-base-price', data.total);

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

    totalVolumeRecount();

    getBaseTariff();
});

$(document).on('change', '.package-weight', function (e) {
    e.preventDefault();

    getBaseTariff();
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

    totalVolumeRecount();

    getBaseTariff();
});

var totalVolumeRecount = function () {

    let totalVolume = 0;
    $.each($(".package-volume"), function(index, item) {
        totalVolume = totalVolume + parseFloat($(item).val().replace(',', '.'));
    });

    $("#total-volume").attr('data-total-volume', totalVolume);
};

$(document).on('change', '.suggest_address', function (e) {
    e.preventDefault();

    let point = $(this);
    $(this).kladr({
        type: $.kladr.type.city,
        source: function (query, callback) {
            var suggestList = point.data('suggestList');
            var matching = [];
            if (suggestList) {
                suggestList.filter(function (value) {
                    return value.name.toLowerCase() == query.toLowerCase();
                });
            }
            if (matching.length > 0)
                callback(matching);
            else
                ymaps.suggest(query, {boundedBy: point.data('seachRect'), results: 10}).then(function (items) {
                    for (var index in suggestList) {
                        var suggestItem = suggestList[index];
                        if (suggestItem.name.toLowerCase().indexOf(query) == 0) {
                            var indexExisting = items.indexOf(suggestItem.displayName);
                            if (indexExisting > -1)
                                items.splice(indexExisting, 1);
                            items.unshift(suggestItem)
                        }
                    }

                    callback(items);
                });
        },
        labelFormat: function (obj, query) {
            return obj.displayName;
        },
        valueFormat: function (obj, query) {
            return obj.value.replace('Россия, ', '');
        },
        select: function (obj) {
            var fullName = obj.value;
            var nameArray = fullName.split(', ');
            point.data('name', nameArray[nameArray.length - 1]);
            point.data('fullName', fullName);
            point.siblings('span').children('input.full_address').val(fullName);
            point.data('region', nameArray[1]);
            if (obj.id !== undefined)
                point.data('id', obj.id);
            else
                point.data('id', 0);

            if (obj.distance !== undefined) {
                point.siblings('input.distance').val(obj.distance);
                calculateToPoint(point);
            } else
                getDistance(point);
        }
    });
});