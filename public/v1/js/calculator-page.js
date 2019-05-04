$(document).ready(function () {
    totalWeigthRecount();
    totalVolumeRecount();

    $('#ship_city').selectize({
        render: {
            option: function (data, escape) {
                return "<div data-terminal='" + data.terminal + "'>" + data.text + "</div>"
            }
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
                data: {ship_city:value}, //здесь мы передаем стандартным пост методом без сериализации. В конечном скрипте данные будут лежать в $_POST['ajax_data']
                cache: false,
                beforeSend: function() {

                },
                success: function(html){
                    $('#dest_city').selectize()[0].selectize.destroy();
                    $('#dest_city').html(html);
                    let select = $('#dest_city').selectize({
                        render: {
                            option: function (data, escape) {
                                return "<div data-terminal='" + data.terminal + "'>" + data.text + "</div>"
                            }
                        },
                        onChange: function(value) {// при изменении города назначения
                            getAllCalculatedData();
                        },
                    });

                    var selectize = select[0].selectize;

                    // Вызываем тригер изменения пункта самовывоза, чтобы пересчитать дистанцию
                    $('#ship_point').trigger('change');
                }
            });
        }
    });
    $('#dest_city').selectize({
        render: {
            option: function (data, escape) {
                return "<div data-terminal='" + data.terminal + "'>" + data.text + "</div>"
            }
        },
        onChange: function(value) {// при изменении города назначения
            // Вызываем тригер изменения пункта самовывоза, чтобы пересчитать дистанцию
            $('#dest_point').trigger('change');
        }
    });

    getAllCalculatedData();

    $('input[name="payer_type"]').click(function () {
        if ($(this).attr("value") == "3-e-lico") {
            $("#3rd-person-payer").show('slow');
        }else{
            $("#3rd-person-payer").hide('slow');
        }
    });

    $(document).on('click', '#add-package-btn', function (e) {
        e.preventDefault();
        var lastId = $( '.package-item' ).filter( ':last' ).data('packageId');
        var nextId = lastId+1;

        console.log(lastId, nextId);

        var html =
            '<div class="col-11 form-item row align-items-center package-item" id="package-'+ nextId +'" data-package-id="'+ nextId +'" style="padding-right: 0;">' +
            '<label class="col-auto calc__label"></label>' +
            '<div class="col calc__inpgrp relative row__inf"  style="padding-right: 0;">' +
            '<div class="input-group">' +
            '<input type="number" id="packages_'+ nextId +'_length" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][length]" data-package-id="'+ nextId +'" data-dimension-type="length" placeholder="Длина" value="0.1">' +
            '<input type="number" id="packages_'+ nextId +'_width" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][width]" data-package-id="'+ nextId +'"  data-dimension-type="width" placeholder="Ширина" value="0.1">' +
            '<input type="number" id="packages_'+ nextId +'_height" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][height]" data-package-id="'+ nextId +'"  data-dimension-type="height" placeholder="Высота" value="0.1">' +
            '<input type="number" id="packages_'+ nextId +'_weight" class="form-control text-center package-params package-weight" name="cargo[packages]['+ nextId +'][weight]" data-package-id="'+ nextId +'"  data-dimension-type="weight" placeholder="Вес" value="1">' +
            '<input type="number" id="packages_'+ nextId +'_quantity" class="form-control text-center package-params package-quantity" name="cargo[packages]['+ nextId +'][quantity]" data-package-id="'+ nextId +'"  data-dimension-type="quantity" placeholder="Места" value="1">' +
            '</div>' +
            '<input type="number" hidden="hidden" id="packages_'+ nextId +'_volume" class="form-control text-center package-params package-volume" name="cargo[packages]['+ nextId +'][volume]" data-package-id="'+ nextId +'"  data-dimension-type="volume"  value="0.001">' +
            '</div>' +
            '</div>';


        $(this).before(html);

        totalVolumeRecount();
        totalWeigthRecount();
        getAllCalculatedData();
    });

    //При изменении параметров пакета
    $('.package-params').on('change', function () {
        let shipCityID = $("#ship_city").val(),
            destCityID = $("#dest_city").val();
        if (shipCityID && destCityID) {
            getAllCalculatedData();
        }
    });

    $(document).on('change', '.custom-service-checkbox', function (e) {
        e.preventDefault();
        getAllCalculatedData();
    });

    $(document).on('change', '#discount', function (e) {
        e.preventDefault();
        getAllCalculatedData();
    });

    $(document).on('change', '#insurance', function (e) {
        $('#insurance-amount').attr('value', '');
        $('#insurance-amount').val('');
        $('#insurance-amount-wrapper').toggle();
    });

    $(document).on('change', '#insurance-amount', function (e) {
        e.preventDefault();
        getAllCalculatedData();
    });

    $(document).on('change', '.package-dimensions', function (e) {
        e.preventDefault();
        let id = $(this).data('packageId'),
            length = $('#packages_'+ id +'_length').val(),
            width = $('#packages_'+ id +'_width').val(),
            height = $('#packages_'+ id +'_height').val(),
            dimensionType = 'max_'+$(this).data('dimensionType'),
            dimensionMax = 0,
            volume = 1;

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

        volume = parseFloat((length * width * height).toFixed(3));

        $('#packages_'+ id +'_volume').attr('value', volume).val(volume);

        totalVolumeRecount();
        getAllCalculatedData();
    });

    $(document).on('change', '.package-weight', function (e) {
        e.preventDefault();

        let id = $(this).data('packageId'),
            length = $('#packages_'+ id +'_length').val(),
            width = $('#packages_'+ id +'_width').val(),
            height = $('#packages_'+ id +'_height').val(),
            volume = $('#packages_'+ id +'_volume').val(),
            dimensionType = 'max_'+$(this).data('dimensionType'),
            dimensionMax = 0;

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
        getAllCalculatedData();
    });

    $(document).on('change', '.package-volume', function (e) {
        e.preventDefault();

        let id = $(this).data('packageId'),
            length = $('#packages_'+ id +'_length').val(),
            width = $('#packages_'+ id +'_width').val(),
            height = $('#packages_'+ id +'_height').val(),
            volume = $('#packages_'+ id +'_volume').val(),
            dimensionType = 'max_'+$(this).data('dimensionType'),
            dimensionMax = 0;

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
        getAllCalculatedData();
    });

    $(document).on('change', '.package-quantity', function (e) {
        e.preventDefault();
        totalWeigthRecount();
        totalVolumeRecount();

        getAllCalculatedData();
    });

    // Включение и отключение инпутов для забора и доставки груза //////////
    $(document).on('change', '#need-to-take', function () {
        if($(this).is(':checked')) {
            $('.need-to-take-input').removeAttr('disabled');
            if($('input[name="need-to-take-type"]:checked').val() === 'from') {
                $('.need-to-take-input-address').removeAttr('disabled');
            }
        } else {
            $('.need-to-take-input').attr('disabled', 'disabled');
            $('.need-to-take-input-address').attr('disabled', 'disabled');
        }

        calcTariffPrice($('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()].terminal, $('#ship_point'), $('input[name="need-to-take-type"]:checked').val() == "in");
    });

    $(document).on('change', 'input[name="need-to-take-type"]', function () {
        if($('input[name="need-to-take-type"]:checked').val() === 'from') {
            $('.need-to-take-input-address').removeAttr('disabled');
        } else if($('input[name="need-to-take-type"]:checked').val() === 'in') {
            $('.need-to-take-input-address').attr('disabled', 'disabled');
        }

        calcTariffPrice($('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()].terminal, $('#ship_point'), $('input[name="need-to-take-type"]:checked').val() == "in");
    });

    $(document).on('change', '#need-to-bring', function () {
        if($(this).is(':checked')) {
            $('.need-to-bring-input').removeAttr('disabled');
            if($('input[name="need-to-bring-type"]:checked').val() === 'from') {
                $('.need-to-bring-input-address').removeAttr('disabled');
            }
        } else {
            $('.need-to-bring-input').attr('disabled', 'disabled');
            $('.need-to-bring-input-address').attr('disabled', 'disabled');
        }

        if($('#dest_city').data().selectize.getValue() !== "") {
            calcTariffPrice($('#dest_city').data().selectize.options[$('#dest_city').data().selectize.getValue()].terminal, $('#dest_point'), $('input[name="need-to-bring-type"]:checked').val() == "in");
        }
    });

    $(document).on('change', 'input[name="need-to-bring-type"]', function () {
        if($('input[name="need-to-bring-type"]:checked').val() === 'from') {
            $('.need-to-bring-input-address').removeAttr('disabled');
        } else if($('input[name="need-to-bring-type"]:checked').val() === 'in') {
            $('.need-to-bring-input-address').attr('disabled', 'disabled');
        }

        if($('#dest_city').data().selectize.getValue() !== "") {
            calcTariffPrice($('#dest_city').data().selectize.options[$('#dest_city').data().selectize.getValue()].terminal, $('#dest_point'), $('input[name="need-to-bring-type"]:checked').val() == "in");
        }
    });

    // Первично инициализируем селекты с кладром
    $('input.suggest_address').on('change', function () {
        let point = $(this);
        let obj = point.kladr('current');

        kladrChange(obj, point);
    }).each(function () { // Инициализация кладра для каждого из селектора
        var point = $(this);
        $(this).kladr({
            // type: $.kladr.type.city, // берем город
            oneString: true, // Если включить эту штуку, то будет возвращаться полный адрес
            select: function (obj) {
                kladrChange(obj, point);
            }
        });
    });

    $(document).on('change', '#ship-from-point', function () {
        getAllCalculatedData();
    });

    $(document).on('change', '#bring-to-point', function () {
        getAllCalculatedData();
    });
});

function getAllCalculatedData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/api/calculator/get-all-calculated',
        data: $('.calculator-form').serialize(),
        dataType: "json",
        cache: false,
        beforeSend: function() {
            console.log('calculate..');
        },
        success: function(data){
            // console.table(data);
            renderCalendar(data);
        },
        error: function(data){
            console.log(data)
        }
    });
}

function totalVolumeRecount() {
    let totalVolume = 0;
    $.each($(".package-volume"), function(index, item) {
        var curAmount = parseFloat($(item).prev('.input-group').find( ".package-quantity" ).val());
        console.log('Amount: ' + curAmount);
        if(isNaN(curAmount)){curAmount = 1;}

        var curVolume = parseFloat($(item).val().replace(',', '.').toString()) * curAmount;
        totalVolume = totalVolume + curVolume;
    });

    $("#total-volume").attr('value', totalVolume).val(totalVolume);
    $("#total-volume-hidden").attr('data-total-volume', totalVolume).attr('value', totalVolume).val(totalVolume);
};

function totalWeigthRecount() {
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

// Базовая функция просчета цены для "Забрать из" и "Доставить"
function calcTariffPrice(city, point, inCity) {
    if(inCity) { // Если работаем в пределах города
        getAllCalculatedData();
    } else { // В противном случае просчитываем километраж с помощью Яндекс api
        let fullName = point.data('fullName');
        if (!fullName)
            return;

        if (city) {
            ymaps.route([city, fullName]).then(function (route) {
                console.log('От: ' + city + ' До: ' + fullName + ' Дистанция: ' + Math.ceil(route.getLength() / 1000));
                $(point.closest('.delivery-block')).find('.distance-hidden-input').val(Math.ceil(route.getLength() / 1000));

                getAllCalculatedData();
            });
        }
    }
}

// Срабатывает при изменении значения селекта выбора города
function kladrChange(obj = null, point) {
    if(obj !== null) {
        let name = obj.type === "Город" ? obj : typeof obj.parents !== "undefined" ? $.grep(obj.parents, function(v) {
            return v.type === "Город";
        })[0] : undefined;

        point.data('name', typeof name === "undefined" ? obj.name : name.name); // Это имя отправляем к нам на сервер
        point.data('fullName', obj.fullName); // Это имя отправляем яндексу для просчета дистанции

        if (obj.id !== undefined)
            point.data('id', obj.id);
        else
            point.data('id', 0);
    }

    if(point.attr('id') === "ship_point") {
        if($('#ship_city').data().selectize.getValue() !== "") {
            $('input[name="take_city_name"]').val(point.data('name'));
            calcTariffPrice($('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()].terminal, point, $('input[name="need-to-take-type"]:checked').val() == "in"); // вызываем просчет для "Забрать из"
        }
    } else {
        if($('#dest_city').data().selectize.getValue() !== "") {
            $('input[name="bring_city_name"]').val(point.data('name'));
            calcTariffPrice($('#dest_city').data().selectize.options[$('#dest_city').data().selectize.getValue()].terminal, point, $('input[name="need-to-bring-type"]:checked').val() == "in"); // вызываем просчет для "Доставить"
        }
    }
}

function renderCalendar(data) {
    if(data.error === undefined) {
        $('#route-name').html(data.route.name);
        $('#base-price').html(data.route.price);

        drawDelivery(data.delivery);
        drawServices(data.services);
        drawDiscount(data.discount);

        $('#total-price').html(data.total);
    }
}

function drawDelivery(delivery) {
    let deliveryPoints = '';

    if(delivery.take !== null) {
        deliveryPoints +=
            '<div class="custom-service-total-item">'+
            '<div class="block__itogo_item d-flex">'+
            '<div class="d-flex flex-wrap">'+
            '<span class="block__itogo_value">' +
            'Забор груза: ' + delivery.take.city_name
            +
            (typeof delivery.take.distance !== "undefined" ? ('<small> (' + delivery.take.distance + ' км) </small>') : '')
            +
            '</span>'+
            '</div>'+
            '<span class="block__itogo_price d-flex flex-nowrap">'+
            '<span class="block__itogo_amount takePrice">' + delivery.take.price.toString() + '</span>'+
            '<span class="rouble">p</span>'+
            '</span>'+
            '</div>'+
            '</div>';
    }

    if(delivery.bring !== null) {
        deliveryPoints +=
            '<div class="custom-service-total-item">'+
            '<div class="block__itogo_item d-flex">'+
            '<div class="d-flex flex-wrap">'+
            '<span class="block__itogo_value">' +
            'Забор груза: ' + delivery.bring.city_name
            +
            (typeof delivery.bring.distance !== "undefined" ? ('<small> (' + delivery.bring.distance + ' км) </small>') : '')
            +
            '</span>'+
            '</div>'+
            '<span class="block__itogo_price d-flex flex-nowrap">'+
            '<span class="block__itogo_amount bringPrice">' + delivery.bring.price.toString() + '</span>'+
            '<span class="rouble">p</span>'+
            '</span>'+
            '</div>'+
            '</div>';
    }

    if(deliveryPoints !== '') {
        $('#delivery-total-list').html(deliveryPoints);
        $('#delivery-total-wrapper').show();
    } else {
        $('#delivery-total-wrapper').hide();
    }
}

function drawServices(services) {
    let servicesPoints = '';

    $.each(services, function(index, item) {
        servicesPoints +=
            '<div class="custom-service-total-item">'+
            '<div class="block__itogo_item d-flex">'+
            '<div class="d-flex flex-wrap">'+
            '<span class="block__itogo_value">' + item.name + '</span>'+
            '</div>'+
            '<span class="block__itogo_price d-flex flex-nowrap">'+
            '<span class="block__itogo_amount">' + item.total + '</span>'+
            '<span class="rouble">p</span>'+
            '</span>'+
            '</div>'+
            '</div>';
    });

    if(servicesPoints !== ''){
        $('#custom-services-total-list').html(servicesPoints);
        $('#custom-services-total-wrapper').show();
    } else {
        $('#custom-services-total-list').html('');
        $('#custom-services-total-wrapper').hide();
    }
}

function drawDiscount(discount) {
    if(discount) {
        $('#custom-services-total-list').append(
            '<div class="custom-service-total-item">'+
            '<div class="block__itogo_item d-flex">'+
            '<div class="d-flex flex-wrap">'+
            '<span class="block__itogo_value">Скидка</span>'+
            '</div>'+
            '<span class="block__itogo_price d-flex flex-nowrap">'+
            '<span class="block__itogo_amount">' + discount + '</span>'+
            '<span class="rouble">p</span>'+
            '</span>'+
            '</div>'+
            '</div>'
        );

        $('#custom-services-total-wrapper').show();
    }
}
