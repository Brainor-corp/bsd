function getDiscount() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/api/calculator/get-discount',
        cache: false,
        success: function(data) {
            $('#discount').val(data);
            $('#discount').trigger('change');
        },
        error: function (data) {
            // console.log(data);
        }
    });
}

let rounded = function(number){
    return +number.toFixed(3);
};

function doorstepChange() {
    let takeSelectId = 'ship_city';
    let takeMessageBlock = 'take-delivery-message';
    let takeMessage = '';

    if(
        $('#' + takeSelectId).data().selectize.getValue()
        && $('#' + takeSelectId).data().selectize.options[$('#' + takeSelectId).data().selectize.getValue()].doorstep === "1"
    ) {
        takeMessage = $('#' + takeSelectId).data().selectize.options[$('#' + takeSelectId).data().selectize.getValue()].doorstep_message;
    }

    let bringSelectId = 'dest_city';
    let bringMessageBlock = 'bring-delivery-message';
    let bringMessage = '';

    if(
        $('#' + bringSelectId).data().selectize.getValue()
        && $('#' + bringSelectId).data().selectize.options[$('#' + bringSelectId).data().selectize.getValue()].doorstep === "1"
    ) {
        bringMessage =  $('#' + bringSelectId).data().selectize.options[$('#' + bringSelectId).data().selectize.getValue()].doorstep_message;
    }

    if(
        $('#' + takeSelectId).data().selectize.options[$('#' + takeSelectId).data().selectize.getValue()]
        && $('#' + takeSelectId).data().selectize.options[$('#' + takeSelectId).data().selectize.getValue()].doorstep === "1"
    ) {
        clearDeliveryData('take', true);
    }

    if(
        $('#' + bringSelectId).data().selectize.options[$('#' + bringSelectId).data().selectize.getValue()]
        && $('#' + bringSelectId).data().selectize.options[$('#' + bringSelectId).data().selectize.getValue()].doorstep === "1"
    ) {
        clearDeliveryData('bring', true);
    }

    if(takeMessage === bringMessage) {
        bringMessage = '';
    }

    if(takeMessage !== null && takeMessage !== undefined) {
        $('#' + takeMessageBlock).text(takeMessage);
    }

    if(bringMessage !== null && bringMessage !== undefined) {
        $('#' + bringMessageBlock).text(bringMessage);
    }
}

$(document).ready(function () {
    getDiscount();
    // totalWeigthRecount();
    // totalVolumeRecount();

    $('.cargo-type-select').selectize({
        maxOptions: 2000
    });

    $('#ship_city').selectize({
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
                $('#ship_city').selectize()[0].selectize.clear();
                clearDeliveryData('take');
                $('#need-to-take').trigger('change');

                $('#dest_city').selectize()[0].selectize.clear();
                clearDeliveryData('bring');
                $('#need-to-bring').trigger('change');
            });
        },
        render: {
            option: function (data, escape) {
                return "<div data-terminal='" + data.terminal
                    + "' data-doorstep='" + data.doorstep
                    + "' data-message='" + data.doorstep_message
                    + "'>" + data.text + "</div>"
            }
        },
        onChange: function(value) {// при изменении города отправления
            doorstepChange();
            getAllCalculatedData();
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
                                $('#dest_city').selectize()[0].selectize.clear();
                                clearDeliveryData('bring');
                                $('#need-to-bring').trigger('change');
                            });
                        },
                        render: {
                            option: function (data, escape) {
                                return "<div data-terminal='" + data.terminal
                                    + "' data-doorstep='" + data.doorstep
                                    + "' data-message='" + data.doorstep_message
                                    + "'>" + data.text + "</div>"
                            }
                        },
                        onChange: function(value) {// при изменении города назначения
                            doorstepChange();
                            getAllCalculatedData();
                            kladrInitialize();
                        },
                    });

                    var selectize = select[0].selectize;

                    // Вызываем тригер изменения пункта самовывоза, чтобы пересчитать дистанцию
                    $('#ship_point').trigger('change');
                    kladrInitialize();
                }
            });
        }
    });
    $('#dest_city').selectize({
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
                $('#dest_city').selectize()[0].selectize.clear();
                clearDeliveryData('bring');
                $('#need-to-bring').trigger('change');
            });
        },
        render: {
            option: function (data, escape) {
                return "<div data-terminal='" + data.terminal
                    + "' data-doorstep='" + data.doorstep
                    + "' data-message='" + data.doorstep_message
                    + "'>" + data.text + "</div>"
            }
        },
        onChange: function(value) {// при изменении города назначения
            // Вызываем тригер изменения пункта самовывоза, чтобы пересчитать дистанцию
            doorstepChange();
            $('#dest_point').trigger('change');
            getAllCalculatedData();
            kladrInitialize();
        }
    });

    kladrInitialize();
    doorstepChange();

    getAllCalculatedData();

    $('input[name="payer_type"]').click(function () {
        if ($(this).attr("value") == "3-e-lico") {
            $("#3rd-person-payer").show('slow', function () {
                updateRequiredInputs();
            });
        }else{
            $("#3rd-person-payer").hide('slow', function () {
                updateRequiredInputs();
            });
        }
    });

    $(document).on('click', '#add-package-btn', function (e) {
        e.preventDefault();
        var lastId = $( '.package-item' ).filter( ':last' ).data('packageId');
        var nextId = lastId+1;

        var html =
            '<div class="row package-wrapper" id="package-wrapper-'+ nextId +'">'+
            '<div class="col-11 form-item row align-items-center package-item" id="package-'+ nextId +'" data-package-id="'+ nextId +'" style="padding-right: 0;">' +
            '<label class="col-auto calc__label"><span class="content">Габариты каждого места (м)* ' +
            '   <span class="d-md-none d-inline-block">(Д/Ш/В/Вес/Кол-во)</span></span>' +
            '</label>' +
            '<div class="col-sm col-12 calc__inpgrp relative row__inf"  style="padding-right: 0;">' +
            '<div class="input-group">' +
            '<input type="number" step="any" min="0" max="12" id="packages_'+ nextId +'_length" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][length]" data-package-id="'+ nextId +'" data-dimension-type="length" placeholder="Длина" value="0.1">' +
            '<input type="number" step="any" min="0" max="2.5" id="packages_'+ nextId +'_width" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][width]" data-package-id="'+ nextId +'"  data-dimension-type="width" placeholder="Ширина" value="0.1">' +
            '<input type="number" step="any" min="0" max="2.5" id="packages_'+ nextId +'_height" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][height]" data-package-id="'+ nextId +'"  data-dimension-type="height" placeholder="Высота" value="0.1">' +
            '<input type="number" step="any" min="0" id="packages_'+ nextId +'_weight" class="form-control text-center package-params package-weight" name="cargo[packages]['+ nextId +'][weight]" data-package-id="'+ nextId +'"  data-dimension-type="weight" placeholder="Вес" value="1">' +
            '<input type="number" step="any" min="0" id="packages_'+ nextId +'_quantity" class="form-control text-center package-params package-quantity" name="cargo[packages]['+ nextId +'][quantity]" data-package-id="'+ nextId +'"  data-dimension-type="quantity" placeholder="Места" value="1">' +
            '</div>' +
            '<input type="number" step="any" hidden="hidden" id="packages_'+ nextId +'_volume" class="form-control text-center package-params package-volume" name="cargo[packages]['+ nextId +'][volume]" data-package-id="'+ nextId +'"  data-dimension-type="volume"  value="0.001">' +
            '</div>' +
            '</div>'+
            '<a href="#" id="delete-package-btn" class=" col-1 align-self-sm-auto align-self-center add_anotherplace" title="Удалить">' +
            '<span class="badge calc_badge"><i class="fa fa-minus"></i></span>' +
            '</a>'+
            '<a href="#" id="add-package-btn" class=" col-1 align-self-sm-auto align-self-center add_anotherplace" title="Добавить">' +
            '<span class="badge calc_badge"><i class="fa fa-plus"></i> место</span>' +
            '</a>'+
            '</div>'
        ;


        $(this).parent().after(html);

        totalVolumeRecount();
        totalWeigthRecount();
        totalQuantityRecount();

        getAllCalculatedData();
    });

    $(document).on('click', '#delete-package-btn', function (e) {
        e.preventDefault();

        $(this).parent().remove();

        totalVolumeRecount();
        totalWeigthRecount();
        getAllCalculatedData();
    });

    //При изменении параметров пакета
    // $('.package-params').on('change', function () {
    //     let shipCityID = $("#ship_city").val(),
    //         destCityID = $("#dest_city").val();
    //     if (shipCityID && destCityID) {
    //         getAllCalculatedData();
    //     }
    // });

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

        if($(this).is(':checked')) {
            $('#insurance-amount').val(50000);
            $('#insurance-amount').attr('required', 'required');
            $('#insurance-amount').attr('min', '50000');
            $('#insurance-amount-wrapper').show();
        } else {
            $('#insurance-amount').val(0);
            $('#insurance-amount').removeAttr('required');
            $('#insurance-amount').removeAttr('min');
            $('#insurance-amount-wrapper').hide();
        }

        getAllCalculatedData();
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
            $(this).addClass('oversized');
            if($(this).parent().parent().children('div.oversize-error').length === 0){
                myDivs = $('<div class="oversize-error">Введенные Вами данные превышают параметры габаритного груза. Возможно увеличение стоимости перевозки.</div>   ')
                    .appendTo($(this).parent().parent())
            }
        }else {
            $(this).removeClass('oversized');
            if($(this).parent().parent().children('div.oversize-error').length !== 0){
                if($(this).parent().children('.oversized').length === 0) {
                    $(this).parent().parent().children('div.oversize-error').remove();
                }
            }
        }

        if(length === ''){
            length = 0.1;
            $('#packages_'+ id +'_length').attr('value', length).val(length);
        } else {
            length = parseFloat(length.replace(',', '.'))
        }

        if(width === ''){
            width = 0.1;
            $('#packages_'+ id +'_width').attr('value', width).val(width);
        } else {
            width = parseFloat(width.replace(',', '.'))
        }

        if(height === '') {
            height = 0.1;
            $('#packages_'+ id +'_height').attr('value', height).val(height);
        } else {
            height = parseFloat(height.replace(',', '.'));
        }

        volume = parseFloat((length * width * height).toFixed(3));

        $('#packages_'+ id +'_volume').attr('value', volume).val(volume);

        totalVolumeRecount();
        // getAllCalculatedData();
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
            $(this).addClass('oversized');
            if($(this).parent().parent().children('div.oversize-error').length === 0){
                myDivs = $('<div class="oversize-error">Введенные Вами данные превышают параметры габаритного груза. Возможно увеличение стоимости перевозки.</div>   ')
                    .appendTo($(this).parent().parent())
            }
        }else {
            $(this).removeClass('oversized');
            if($(this).parent().parent().children('div.oversize-error').length !== 0){
                if($(this).parent().children('.oversized').length === 0) {
                    $(this).parent().parent().children('div.oversize-error').remove();
                }
            }
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
        if($(this).val() >dimensionMax){
            $(this).addClass('oversized');
            if($(this).parent().parent().children('div.oversize-error').length === 0){
                myDivs = $('<div class="oversize-error">Введенные Вами данные превышают параметры габаритного груза. Возможно увеличение стоимости перевозки.</div>   ')
                    .appendTo($(this).parent().parent())
            }
        }else {
            $(this).removeClass('oversized');
            if($(this).parent().parent().children('div.oversize-error').length !== 0){
                if($(this).parent().children('.oversized').length === 0) {
                    $(this).parent().parent().children('div.oversize-error').remove();
                }
            }
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
        // getAllCalculatedData();
    });

    $(document).on('change', '.package-quantity', function (e) {
        e.preventDefault();
        totalWeigthRecount();
        totalVolumeRecount();
        totalQuantityRecount();

        // getAllCalculatedData();
    });

    // Включение и отключение инпутов для забора и доставки груза //////////
    $(document).on('change', '#need-to-take', function () {
        if($(this).is(':checked')) {
            $('.need-to-take-input').removeAttr('disabled');
            $('.need-to-take-input-address').removeAttr('disabled');
            $('.need-to-take-input-address').attr('required', 'required');
        } else {
            $('.need-to-take-input').attr('disabled', 'disabled');
            $('.need-to-take-input').prop('checked', false);
            $('.need-to-take-input-address').attr('disabled', 'disabled');
            $('.need-to-take-input-address').removeAttr('required');
            clearDeliveryData('take');
        }

        if($('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()] !== undefined) {
            calcTariffPrice(
                {
                    'value': $('#ship_city').val(),
                    'points': $('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()].terminal
                },
                $('#ship_point'),
                $('input[name="need-to-take-type"]:checked').val() == "in"
            );
        }
    });

    $(document).on('change', 'input[name="need-to-take-type"]', function () {
        if($(this).is(':checked')) {
            if ($('input[name="need-to-take-type"]:checked').val() === 'from') {
                $('.need-to-take-input-address').removeAttr('disabled');
            } else if ($('input[name="need-to-take-type"]:checked').val() === 'in') {
                $('.need-to-take-input-address').attr('disabled', 'disabled');
            }

            calcTariffPrice(
                {
                    'value': $('#ship_city').val(),
                    'point': $('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()].terminal
                },
                $('#ship_point'), $('input[name="need-to-take-type"]:checked').val() == "in"
            );
        }
    });

    $(document).on('change', '#need-to-bring', function () {
        if($(this).is(':checked')) {
            $('.need-to-bring-input').removeAttr('disabled');
            $('.need-to-bring-input-address').removeAttr('disabled');
            $('.need-to-bring-input-address').attr('required', 'required');
        } else {
            $('.need-to-bring-input').attr('disabled', 'disabled');
            $('.need-to-bring-input').prop('checked', false);
            $('.need-to-bring-input-address').attr('disabled', 'disabled');
            $('.need-to-bring-input-address').removeAttr('required');
            clearDeliveryData('bring');
        }

        if($('#dest_city').data().selectize.getValue() !== "") {
            calcTariffPrice(
                {
                    'value': $('#dest_city').val(),
                    'point': $('#dest_city').data().selectize.options[$('#dest_city').data().selectize.getValue()].terminal
                },
                $('#dest_point'), $('input[name="need-to-bring-type"]:checked').val() == "in"
            );
        }
    });

    $(document).on('change', 'input[name="need-to-bring-type"]', function () {
        if($('input[name="need-to-bring-type"]:checked').val() === 'from') {
            $('.need-to-bring-input-address').removeAttr('disabled');
        } else if($('input[name="need-to-bring-type"]:checked').val() === 'in') {
            $('.need-to-bring-input-address').attr('disabled', 'disabled');
        }

        if($('#dest_city').data().selectize.getValue() !== "") {
            calcTariffPrice(
                {
                    'value': $('#dest_city').val(),
                    'point': $('#dest_city').data().selectize.options[$('#dest_city').data().selectize.getValue()].terminal
                },
                $('#dest_point'), $('input[name="need-to-bring-type"]:checked').val() == "in"
            );
        }
    });

    // Первично инициализируем селекты с кладром
    $('input.suggest_address').on('keyup', function () {
        let point = $(this);
        if(point.val().length > 2){
            let obj = point.kladr('current');
            kladrChange(obj, point);
        }
    });
    function kladrInitialize() {
        $('input.suggest_address').each(function (indx, element) { // Инициализация кладра для каждого из селектора
            var point = $(element);
            var city = '';
            var cityKladrId = '';
            if(
                point.attr('id') === 'ship_point' &&
                $('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()] !== undefined
            ){
                city = $('#ship_city').text();
                cityKladrId = $('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()].kladrId;
            }

            if(
                point.attr('id') === 'dest_point' &&
                $('#dest_city').data().selectize.options[$('#dest_city').data().selectize.getValue()] !== undefined
            ) {
                city = $('#dest_city').text();
                cityKladrId = $('#dest_city').data().selectize.options[$('#dest_city').data().selectize.getValue()].kladrId;
            }
            $(element).kladr({
                // type: $.kladr.type.city, // берем город
                oneString: true, // Если включить эту штуку, то будет возвращаться полный адрес
                parentType: $.kladr.type.city,
                parentId: cityKladrId,
                source: async function (query, callback) {
                    let points = [];
                    let yandexSuggests = [];

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    await $.ajax({
                        type: 'get',
                        url: '/api/points-by-term',
                        data: {
                            'term': query.name,
                            'city_name': point.attr('id') === "ship_point" ?
                                $('#ship_city').data().selectize.getValue() :
                                $('#dest_city').data().selectize.getValue()
                        },
                        dataType: "json",
                        cache: false,
                        success: function (response) {
                            points = response.data;
                        }
                    });

                    await ymaps.suggest(query.name, {results: 10}).then(function (items) {
                        yandexSuggests = items;
                    });

                    let result = [...points, ...yandexSuggests];

                    callback(result);
                },
                labelFormat: function (obj, query) {
                    return obj.displayName;
                },
                valueFormat: function (obj, query) {
                    return obj.value.replace('Россия, ', '');
                },
                select: function (obj) {
                    kladrChange(obj, point);
                }
            });
        });
    }

    // Срабатывает при изменении значения селекта выбора города
    function kladrChange(obj = null, point) {
        if(obj !== null) {
            var locality = '';
            ymaps.geocode(obj.value, {results: 1}).then(function (res) {
                let forceDeliveryType = null;

                if(obj.isPoint) {
                    locality = obj.value;
                } else {
                    locality = res.geoObjects.get(0).getLocalities()[0];
                    if(locality === undefined) {
                        forceDeliveryType = 'from';
                        if(point.attr('id') === "ship_point") {
                            locality = $('select[name="ship_city"] option:selected').val();
                        } else {
                            locality = $('select[name="dest_city"] option:selected').val();
                        }
                    }
                }

                point.data('name', locality).attr('data-name', locality); // Это имя отправляем к нам на сервер
                point.data('fullName', obj.value).attr('data-full-name', obj.value); // Это имя отправляем яндексу для просчета дистанции

                if (obj.id !== undefined)
                    point.data('id', obj.id);
                else
                    point.data('id', 0);

                if(point.attr('id') === "ship_point") {
                    if($('#ship_city').data().selectize.getValue() !== "") {
                        changeDeliveryType($('select[name="ship_city"] option:selected').val(), locality, obj.value, "need-to-take-type", forceDeliveryType);
                        $('input[name="take_city_name"]').val(point.data('name'));
                        calcTariffPrice(
                            {
                                'value': $('#ship_city').val(),
                                'point': $('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()].terminal
                            },
                            point,
                            $('input[name="need-to-take-type"]:checked').val() == "in"
                        ); // вызываем просчет для "Забрать из"
                    }
                } else {
                    if($('#dest_city').data().selectize.getValue() !== "") {
                        changeDeliveryType($('select[name="dest_city"] option:selected').val(), locality, obj.value, "need-to-bring-type", forceDeliveryType);
                        $('input[name="bring_city_name"]').val(point.data('name'));
                        calcTariffPrice(
                            {
                                'value': $('#dest_city').val(),
                                'point': $('#dest_city').data().selectize.options[$('#dest_city').data().selectize.getValue()].terminal
                            },
                            point, $('input[name="need-to-bring-type"]:checked').val() == "in"
                        ); // вызываем просчет для "Доставить"
                    }
                }
            }, function (err) {
                // Обработка ошибки.
            });
            // let name = obj.type === "Город" ? obj : typeof obj.parents !== "undefined" ? $.grep(obj.parents, function(v) {
            //     return v.type === "Город";
            // })[0] : undefined;
        }
    }

    $(document).on('change', '#ship-from-point', function () {
        getAllCalculatedData();
    });

    $(document).on('change', '#bring-to-point', function () {
        getAllCalculatedData();
    });

    function updateRequiredInputs() {
        $('.req:hidden').removeAttr('required');
        $('.req:visible').attr('required', 'required');

        if($('#sender_legal_form').val() === 'ИП') {
            $('#sender_kpp').removeAttr('required');
            $('label[for="sender_kpp"]').text('КПП');
        } else {
            $('label[for="sender_kpp"]').text('КПП*');
        }

        if($('#recipient_legal_form').val() === 'ИП') {
            $('#recipient_kpp').removeAttr('required');
            $('label[for="recipient_kpp"]').text('КПП');
        } else {
            $('label[for="recipient_kpp"]').text('КПП*');
        }

        if($('#payer_legal_form').val() === 'ИП') {
            $('#payer_kpp').removeAttr('required');
            $('label[for="payer_kpp"]').text('КПП');
        } else {
            $('label[for="payer_kpp"]').text('КПП*');
        }
    }

    updateRequiredInputs();

    $(document).on('change', 'input[name="sender_type_id"]', function () {
        let currentSlug = $('input[name="sender_type_id"]:checked').data('slug');

        if(currentSlug === 'fizicheskoe-lico') {
            $('.sender-forms .legal').slideUp('slow', function () {
                updateRequiredInputs();
            });
            $('.sender-forms .individual').slideDown('slow', function () {
                updateRequiredInputs();
            });
        } else if(currentSlug === 'yuridicheskoe-lico') {
            $('.sender-forms .legal').slideDown('slow', function () {
                updateRequiredInputs();
            });
            $('.sender-forms .individual').slideUp('slow', function () {
                updateRequiredInputs();
            });
        }
    });

    $(document).on('change', 'input[name="recipient_type_id"]', function () {
        let currentSlug = $('input[name="recipient_type_id"]:checked').data('slug');

        if(currentSlug === 'fizicheskoe-lico') {
            $('.recipient-forms .legal').slideUp('slow', function () {
                updateRequiredInputs();
            });
            $('.recipient-forms .individual').slideDown('slow', function () {
                updateRequiredInputs();
            });
        } else if(currentSlug === 'yuridicheskoe-lico') {
            $('.recipient-forms .legal').slideDown('slow', function () {
                updateRequiredInputs();
            });
            $('.recipient-forms .individual').slideUp('slow', function () {
                updateRequiredInputs();
            });
        }
    });

    $(document).on('change', 'input[name="payer_form_type_id"]', function () {
        let currentSlug = $('input[name="payer_form_type_id"]:checked').data('slug');
        $('input[name="payer_form_type_id"]').attr('required', 'required');

        if(currentSlug === 'fizicheskoe-lico') {
            $('.payer-forms .legal').slideUp('slow', function () {
                updateRequiredInputs();
            });
            $('.payer-forms .individual').slideDown('slow', function () {
                updateRequiredInputs();
            });
        } else if(currentSlug === 'yuridicheskoe-lico') {
            $('.payer-forms .legal').slideDown('slow', function () {
                updateRequiredInputs();
            });
            $('.payer-forms .individual').slideUp('slow', function () {
                updateRequiredInputs();
            });
        }
    });

    $(document).on('change', '#sender_legal_form', function () {
        if($('#sender_legal_form').val() === 'ИП') {
            $('#sender_kpp').removeAttr('required');
            $('label[for="sender_kpp"]').text('КПП');
        } else {
            $('#sender_kpp').attr('required', 'required');
            $('label[for="sender_kpp"]').text('КПП*');
        }
    });

    $(document).on('change', '#recipient_legal_form', function () {
        if($('#recipient_legal_form').val() === 'ИП') {
            $('#recipient_kpp').removeAttr('required');
            $('label[for="recipient_kpp"]').text('КПП');
        } else {
            $('#recipient_kpp').attr('required', 'required');
            $('label[for="recipient_kpp"]').text('КПП*');
        }
    });

    $(document).on('change', '#payer_legal_form', function () {
        if($('#payer_legal_form').val() === 'ИП') {
            $('#payer_kpp').removeAttr('required');
            $('label[for="payer_kpp"]').text('КПП');
        } else {
            $('#payer_kpp').attr('required', 'required');
            $('label[for="payer_kpp"]').text('КПП*');
        }
    });

    $(".individual input.autocomplete").autocomplete({
        source: function (request, response) {
            jQuery.get($(this.element).data('source'), {
                term: request.term,
                field: $(this.element).data('field')
            }, function (data) {
                let result = [];
                $(data).each(function (key, el) {
                    result.push({
                        label: el['name'] + " (Паспорт: " + el['passport_series'] + el['passport_number'] + ")",
                        name: el['name'],
                        passport_number: el['passport_number'],
                        passport_series: el['passport_series'],
                        phone: el['phone'],
                        contact_person: el['contact_person'],
                        addition_info: el['addition_info'],
                    });
                });
                response(result);
            });
        },
        select: function( event, ui ) {
            event.preventDefault();

            let currentBlock = $(event.target).closest('.individual');
            currentBlock.find("input[name$='name_individual']").val(ui.item["name"]);
            currentBlock.find("input[name$='passport_series']").val(ui.item["passport_series"]);
            currentBlock.find("input[name$='passport_number']").val(ui.item["passport_number"]);
            currentBlock.find("input[name$='phone_individual']").val(ui.item["phone"]);
            currentBlock.find("input[name$='contact_person_individual']").val(ui.item["contact_person"]);
            currentBlock.find("input[name$='addition_info_individual']").val(ui.item["addition_info"]);
        }
    });

    $(".legal input.autocomplete").autocomplete({
        source: function (request, response) {
            jQuery.get($(this.element).data('source'), {
                term: request.term,
                field: $(this.element).data('field')
            }, function (data) {
                let result = [];
                $(data).each(function (key, el) {
                    result.push({
                        label: el['company_name'] + " (ИНН: " + el['inn'] + ")",
                        phone: el['phone'],
                        company_name: el['company_name'],
                        contact_person: el['contact_person'],
                        addition_info: el['addition_info'],
                        address_city: el['legal_address_city'],
                        address: el['legal_address'],
                        legal_form: el['legal_form'],
                        inn: el['inn'],
                        kpp: el['kpp'],
                    });
                });
                response(result);
            });
        },
        select: function( event, ui ) {
            event.preventDefault();

            let currentBlock = $(event.target).closest('.legal');
            currentBlock.find("input[name$='phone_legal']").val(ui.item["phone"]);
            currentBlock.find("input[name$='company_name']").val(ui.item["company_name"]);
            currentBlock.find("input[name$='contact_person_legal']").val(ui.item["contact_person"]);
            currentBlock.find("input[name$='addition_info_legal']").val(ui.item["addition_info"]);
            currentBlock.find("input[name$='legal_form']").val(ui.item["legal_form"]);
            currentBlock.find("input[name$='address_city']").val(ui.item["address_city"]);
            currentBlock.find("input[name$='address']").val(ui.item["address"]);
            currentBlock.find("input[name$='inn']").val(ui.item["inn"]);
            currentBlock.find("input[name$='kpp']").val(ui.item["kpp"]);
        }
    });

    $(document).on('focusin', '.package-params, #total-weight, #total-volume, #total-quantity', function (event) {
        $(this).val('');
    })
});

import { getAllCalculatedData } from "./get-all-data.js";

function totalVolumeRecount() {
    let totalVolume = 0;
    $.each($(".package-volume"), function(index, item) {
        var curAmount = parseFloat($(item).prev('.input-group').find( ".package-quantity" ).val());
        if(isNaN(curAmount)){curAmount = 0;}

        var curVolume = parseFloat($(item).val().replace(',', '.').toString()) * curAmount;
        totalVolume += curVolume;
    });

    $("#total-volume").attr('value', totalVolume).val(rounded(totalVolume));
    $("#total-volume").trigger('change');
};

function totalQuantityRecount() {
    let totalQuantity = 0;
    $.each($(".package-quantity"), function(index, item) {
        var curAmount = parseFloat($(item).val());
        if(isNaN(curAmount)){curAmount = 0;}

        totalQuantity += curAmount;
    });

    $("#total-quantity").attr('value', totalQuantity).val(totalQuantity);
    $("#total-quantity").trigger('change');
}

$(document).on('change', '#total-volume', () => getAllCalculatedData());

$(document).on('change', '#total-quantity', () => getAllCalculatedData());

function totalWeigthRecount() {
    let totalWeigth = 0;
    $.each($(".package-weight"), function(index, item) {

        var curAmount = parseFloat($(item).next('.package-quantity').val().replace(',', '.'));
        if(isNaN(curAmount)){curAmount = 1;}
        var curWeigth = parseFloat($(item).val().replace(',', '.')) * curAmount;
        totalWeigth = totalWeigth + curWeigth;
    });

    $("#total-weight").attr('value', totalWeigth).val(totalWeigth);
    $("#total-weight").trigger('change');
};

$(document).on('change', '#total-weight', () => getAllCalculatedData());

import { calcTariffPrice } from "./calc-tariff-price.js";
import {checkAddressInPolygon} from "./ckeck-address-in-polygon.js";

function changeDeliveryType(cityFrom, cityTo, address, inputName, forceDeliveryType = null) {
    if(forceDeliveryType !== null) {
        $('input:radio[name="' + inputName + '"]').filter('[value="' + forceDeliveryType + '"]').prop('checked', true);
        return;
    }

    let type = 'from';

    if(cityFrom !== cityTo) {
        $('input:radio[name="' + inputName + '"]').filter('[value="' + type + '"]').prop('checked', true);
        return;
    }

    // Пробуем получить полигоны для выбранного города
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/api/calculator/get-city-polygons',
        data: {
            city: cityFrom
        },
        dataType: "json",
        cache: false,
        success: async function(data) {
            if(data.length) {
                let isInPolygon;

                for (const el of data) {
                    let findCoordinates = [];
                    let polygonCoordinates = el.coordinates.match(/\[\d+\.\d+\,\s*\d+\.\d+\]/g);
                    $(polygonCoordinates).each(function (pairKey, pairVal) {
                        pairVal = pairVal.replace(' ', '');
                        pairVal = pairVal.replace('[', '');
                        pairVal = pairVal.replace(']', '');
                        let parts = pairVal.split(',');
                        findCoordinates.push([
                            parseFloat(parts[0]),
                            parseFloat(parts[1])
                        ]);
                    });

                    let polygon =  new ymaps.Polygon([findCoordinates]);
                    isInPolygon = await checkAddressInPolygon(address, polygon);
                    if(isInPolygon) {
                        type = 'in';
                        break;
                    }
                }
            } else {
                type = 'in';
            }

            $('input:radio[name="' + inputName + '"]').filter('[value="' + type + '"]').prop('checked', true);

            return type;
        },
        error: function(data){
            // console.log(data);
        }
    });
}

function clearDeliveryData(type, disable = false) {
    let pointType = type === 'take' ? 'ship': 'dest';

    $('#' + pointType + '_point').val('');
    $('#' + pointType + '_point').data('name', '').removeAttr('data-name');
    $('#' + pointType + '_point').data('full-name', '').removeAttr('data-full-name');
    $('input[name="' + type + '_city_name"]').val('');
    $('input[name="' + type + '_distance"]').val('');
    $('input[name="' + type + '_polygon"]').val('');

    $('#need-to-' + type).prop("checked", false);
    $('#need-to-' + type).prop("disabled", disable);
}
