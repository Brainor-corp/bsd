﻿$('#ship_city').selectize({
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

        // Вызываем тригер изменения пункта самовывоза, чтобы пересчитать дистанцию
        $('#ship_point').trigger('change');
    }
});

$('#dest_city').selectize({
    onChange: function(value) {// при изменении города назначения
        getRoute();

        // Вызываем тригер изменения пункта самовывоза, чтобы пересчитать дистанцию
        $('#dest_point').trigger('change');
    }
});

$(document).on('click', '#add-package-btn', function (e) {
    e.preventDefault();
    var lastId = $( '.package-item' ).filter( ':last' ).data('packageId');
    var nextId = lastId+1;
    // var html =
    //         '<div id="package-'+ nextId +'" class="package-item" data-package-id="'+ nextId +'">'+
    //                 '<div class="form-item row align-items-center">'+
    //                 '<label class="col-auto calc__label">Наименование груза*</label>'+
    //             '<div class="col">'+
    //                 '<input type="text" class="form-control" placeholder="Шкаф" name="packages['+ nextId +'][name]">'+
    //         '</div>'+
    //             '</div>'+
    //             '<div class="row">'+
    //                 '<div class="col-8">'+
    //                 '<div class="form-item row align-items-center">'+
    //                 '<label class="col-auto calc__label">Габариты (м)*</label>'+
    //                 '<div class="col calc__inpgrp relative row__inf">'+
    //                 '<div class="input-group">'+
    //                 '<input type="text" id="packages_'+ nextId +'_length" class="form-control text-center package-params package-dimensions" name="packages['+ nextId +'][length]" data-package-id="'+ nextId +'" data-dimension-type="length" placeholder="Д"/>'+
    //         '<input type="text" id="packages_'+ nextId +'_width" class="form-control text-center package-params package-dimensions" name="packages['+ nextId +'][width]" data-package-id="'+ nextId +'" data-dimension-type="width" placeholder="Ш">'+
    //         '<input type="text" id="packages_'+ nextId +'_height" class="form-control text-center package-params package-dimensions" name="packages['+ nextId +'][height]" data-package-id="'+ nextId +'" data-dimension-type="height" placeholder="В"/>'+
    //         '</div>'+
    //             '</div>'+
    //             '</div>'+
    //             '<div class="form-item row align-items-center">'+
    //                 '<label class="col-auto calc__label">Вес груза (кг)*</label>'+
    //             '<div class="col calc__inpgrp"><input type="text" id="packages_'+ nextId +'_weight" class="form-control package-params package-weight" name="packages['+ nextId +'][weight]" data-package-id="'+ nextId +'" data-dimension-type="weight"/></div>'+
    //             '</div>'+
    //             '<div class="form-item row align-items-center">'+
    //                 '<label class="col-auto calc__label">Объем (м<sup>3</sup>)*</label>'+
    //             '<div class="col calc__inpgrp"><input type="text" id="packages_'+ nextId +'_volume" class="form-control package-params package-volume" name="packages['+ nextId +'][volume]" data-package-id="'+ nextId +'" data-dimension-type="volume"/></div>'+
    //             '</div>'+
    //             '</div>'+
    //             '<div class="col-4">'+
    //                 '<p class="calc__info">Габариты груза влияют на расчет стоимости, без их указания стоимость может быть неточной</p>'+
    //             '</div>'+
    //             '</div>'+
    //             '</div>'
    // ;

    var html =
        '<div class="col-11 form-item row align-items-center package-item" id="package-'+ nextId +'" data-package-id="'+ nextId +'" style="padding-right: 0;">' +
        '<label class="col-auto calc__label"></label>' +
        '<div class="col calc__inpgrp relative row__inf"  style="padding-right: 0;">' +
        '<div class="input-group">' +
        '<input type="text" id="packages_'+ nextId +'_length" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][length]" data-package-id="'+ nextId +'" data-dimension-type="length" placeholder="Длина" value="0.1">' +
        '<input type="text" id="packages_'+ nextId +'_width" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][width]" data-package-id="'+ nextId +'"  data-dimension-type="width" placeholder="Ширина" value="0.1">' +
        '<input type="text" id="packages_'+ nextId +'_height" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][height]" data-package-id="'+ nextId +'"  data-dimension-type="height" placeholder="Высота" value="0.1">' +
        '<input type="text" id="packages_'+ nextId +'_weight" class="form-control text-center package-params package-weight" name="cargo[packages]['+ nextId +'][weight]" data-package-id="'+ nextId +'"  data-dimension-type="weight" placeholder="Вес" value="1">' +
        '<input type="text" id="packages_'+ nextId +'_quantity" class="form-control text-center package-params package-quantity" name="cargo[packages]['+ nextId +'][quantity]" data-package-id="'+ nextId +'"  data-dimension-type="quantity" placeholder="Места" value="1">' +
        '</div>' +
        '<input type="text" hidden="hidden" id="packages_'+ nextId +'_volume" class="form-control text-center package-params package-volume" name="cargo[packages]['+ nextId +'][volume]" data-package-id="'+ nextId +'"  data-dimension-type="volume"  value="0.01">' +
        '</div>' +
        '</div>';


    $(this).before(html);

    totalVolumeRecount();

    totalWeigthRecount();
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

    console.log(shipCityID);
    console.log(destCityID);
    console.log(formData);

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
                servicesRender(data.total_data);
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
    $.ajax({
        type: 'post',
        url: '/api/calculator/get-total-price',
        data: {ship_city:shipCityID, dest_city:destCityID, base_price:basePrice, total_volume:totalVolume, formData},
        cache: false,
        beforeSend: function() {

        },
        success: function(data){

            servicesRender(data);

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
        dimensionType = 'max_'+$(this).data('dimensionType'),
        dimensionMax = 0,
        volume = 1
    ;

    dimensionMax = parameters[dimensionType];
    if($(this).val() >dimensionMax){
        $(this).css({
            'background': 'rgba(255, 177, 177, 0.25)',
        });
    }else {
        $(this).css({
            'background': 'transparent',
        });
    }

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

    let id = $(this).data('packageId'),
        length = $('#packages_'+ id +'_length').val(),
        width = $('#packages_'+ id +'_width').val(),
        height = $('#packages_'+ id +'_height').val(),
        volume = $('#packages_'+ id +'_volume').val(),
        dimensionType = 'max_'+$(this).data('dimensionType'),
        dimensionMax = 0
    ;

    dimensionMax = parameters[dimensionType];
    if($(this).val() >dimensionMax){
        $(this).css({
            'background': 'rgba(255, 177, 177, 0.25)',
        });
    }else {
        $(this).css({
            'background': 'transparent',
        });
    }

    totalWeigthRecount();

    getBaseTariff();
});

$(document).on('change', '.package-volume', function (e) {
    e.preventDefault();

    let id = $(this).data('packageId'),
        length = $('#packages_'+ id +'_length').val(),
        width = $('#packages_'+ id +'_width').val(),
        height = $('#packages_'+ id +'_height').val(),
        volume = $('#packages_'+ id +'_volume').val(),
        dimensionType = 'max_'+$(this).data('dimensionType'),
        dimensionMax = 0
    ;


    dimensionMax = parameters[dimensionType];
    if($(this).val() > dimensionMax){
        $(this).css({
            'background': 'rgba(255, 177, 177, 0.25)',
        });
    }else {
        $(this).css({
            'background': 'transparent',
        });
    }

    if(volume !== ''){
        if(length !== '' && width !== '' && height !== ''){
            height = volume/(length*width);
            $('#packages_'+ id +'_height').attr('value', height).val(height);
            if(height > parameters['max_height']){
                $('#packages_'+ id +'_height').css({
                    'background': 'rgba(255, 177, 177, 0.25)',
                });
            }else {
                $('#packages_'+ id +'_height').css({
                    'background': 'transparent',
                });
            }
        }
        if(length !== '' && width !== '' && height === ''){
            width = volume/length;
            $('#packages_'+ id +'_width').attr('value', width).val(width);
            if(width >parameters['max_width']){
                $('#packages_'+ id +'_width').css({
                    'background': 'rgba(255, 177, 177, 0.25)',
                });
            }else {
                $('#packages_'+ id +'_width').css({
                    'background': 'transparent',
                });
            }
        }
    }
    totalVolumeRecount();

    getBaseTariff();
});

$(document).on('change', '.package-quantity', function (e) {
    e.preventDefault();

    totalWeigthRecount();

    totalVolumeRecount();

    getBaseTariff();
});

var totalVolumeRecount = function () {

    let totalVolume = 0;
    $.each($(".package-volume"), function(index, item) {
        var curAmount = parseFloat($(item).prev('.input-group').find( ".package-quantity" ).val());

        if(isNaN(curAmount)){curAmount = 1;}
        var curVolume = parseFloat($(item).val().replace(',', '.')) * curAmount;
        totalVolume = totalVolume + curVolume;
    });

    $("#total-volume").attr('value', totalVolume).val(totalVolume);
    $("#total-volume-hidden").attr('data-total-volume', totalVolume).attr('value', totalVolume).val(totalVolume);
};

var totalWeigthRecount = function () {

    let totalWeigth = 0;
    $.each($(".package-weight"), function(index, item) {

        var curAmount = parseFloat($(item).next('.package-quantity').val().replace(',', '.'));
        if(isNaN(curAmount)){curAmount = 1;}
        var curWeigth = parseFloat($(item).val().replace(',', '.')) * curAmount;
        totalWeigth = totalWeigth + curWeigth;
    });

    $("#total-weight").attr('value', totalWeigth).val(totalWeigth);
    $("#total-weight-hidden").attr('data-total-weight', totalWeigth).attr('value', totalWeigth).val(totalWeigth);
};

var servicesRender = function (data) {
    $('#custom-services-total-wrapper').css({
        'display': 'none',
    });
    let services = '';

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
};

//// Просчет суммы "Забрать груз из" ////////////////////////

// Вспомогательная функция для отправки ajax на сервер для получения цены для "Забрать из"
function getPickUpPriceAjax(point, isWithinTheCity, distance = null) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/api/calculator/get-pick-up-price',
        data: {
            point: point.data('name'), // Название города
            weight: $('#total-weight').val(),
            volume: $('#total-volume').val(),
            units: $('.package-item').length,
            // region: point.data('region'),
            distance: distance, // Километраж
            isWithinTheCity: isWithinTheCity, // Флаг работы в пределах города
            x2: $('#ship-from-point').is(":checked") // Умножим цену на 2, если нужна точная доставка
        },
        cache: false,
        beforeSend: function() {

        },
        success: function(data){
            console.log(data);
        },
        error: function (data) {
            console.log(data);
        }
    });
}

// Базовая функция просчета цены для "Забрать из"
function calcPickUpPrice(cityFrom, point) {
    if(cityFrom === point.data('fullName')) { // Если названия городов совпадают, то работаем в пределах города
        getPickUpPriceAjax(point, true);
    } else { // Противном случае просчитываем километраж с помощью Яндекс api
        let fullName = point.data('fullName');
        if (!fullName)
            return;

        // Находим ближайший селектор города и берем его значение
        let terminal = point.closest('.block-for-distance').find('.point-select option:selected').text();

        if (terminal) {
            ymaps.route([terminal, fullName], {mapStateAutoApply: true})
                .then(function (route) {
                    getPickUpPriceAjax(point, false, Math.ceil(route.getLength() / 1000));
                });
        }
    }
}

// Первично инициализируем селекты с кладром
$('input.suggest_address').on('change', function () {
    var point = $(this);

    if(point.attr('id') === "ship_point") { // при изменении селекта вызываем просчет цены
        calcPickUpPrice($('#ship_city option:selected').text(), point); // для "Забрать из"
    } else {
        // todo для "Доставить в"
    }
}).each(function () { // Инициализация кладра для каждого из селектора
    var point = $(this);
    $(this).kladr({
        type: $.kladr.type.city, // берем город
        // oneString: true, // Если включить эту штуку, то будет возвращаться полный адрес
        select: function (obj) {
            var fullName = obj.name;
            var nameArray = fullName.split(', ');
            point.data('name', nameArray[nameArray.length - 1]); // Это имя отправляем к нам на сервер
            point.data('fullName', fullName); // Это имя отправляем яндексу для просчета дистанции
            // point.data('region', nameArray[1]);

            if (obj.id !== undefined)
                point.data('id', obj.id);
            else
                point.data('id', 0);

            if(point.attr('id') === "ship_point") {
                calcPickUpPrice($('#ship_city option:selected').text(), point); // вызываем просчет для "Забрать из"
            } else {
                // todo просчет для "Доставить в"
            }
        }
    });
});