let map;

ymaps.ready(function () {
    map = new ymaps.Map ("hiddenMap", {
        center: [55.76, 37.64],
        zoom: 7
    });
});

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
                $('#need-to-take').prop("checked", false);
                clearDeliveryData('take');
                $('#need-to-take').trigger('change');

                $('#dest_city').selectize()[0].selectize.clear();
                $('#need-to-bring').prop("checked", false);
                clearDeliveryData('bring');
                $('#need-to-bring').trigger('change');
            });
        },
        render: {
            option: function (data, escape) {
                return "<div data-terminal='" + data.terminal + "'>" + data.text + "</div>"
            }
        },
        onChange: function(value) {// при изменении города отправления
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
                                $('#need-to-bring').prop("checked", false);
                                clearDeliveryData('bring');
                                $('#need-to-bring').trigger('change');
                            });
                        },
                        render: {
                            option: function (data, escape) {
                                return "<div data-terminal='" + data.terminal + "'>" + data.text + "</div>"
                            }
                        },
                        onChange: function(value) {// при изменении города назначения
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
                $('#need-to-bring').prop("checked", false);
                clearDeliveryData('bring');
                $('#need-to-bring').trigger('change');
            });
        },
        render: {
            option: function (data, escape) {
                return "<div data-terminal='" + data.terminal + "'>" + data.text + "</div>"
            }
        },
        onChange: function(value) {// при изменении города назначения
            // Вызываем тригер изменения пункта самовывоза, чтобы пересчитать дистанцию
            $('#dest_point').trigger('change');
            getAllCalculatedData();
            kladrInitialize();
        }
    });

    kladrInitialize();

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
            '<label class="col-auto calc__label"><span class="content">Габариты (м)* <span class="d-md-none d-inline-block">(Д/Ш/В/Вес/Кол-во)</span></span></label>' +
            '<div class="col-sm col-12 calc__inpgrp relative row__inf"  style="padding-right: 0;">' +
            '<div class="input-group">' +
            '<input type="number" step="any" min="0" id="packages_'+ nextId +'_length" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][length]" data-package-id="'+ nextId +'" data-dimension-type="length" placeholder="Длина" value="0.1">' +
            '<input type="number" step="any" min="0" id="packages_'+ nextId +'_width" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][width]" data-package-id="'+ nextId +'"  data-dimension-type="width" placeholder="Ширина" value="0.1">' +
            '<input type="number" step="any" min="0" id="packages_'+ nextId +'_height" class="form-control text-center package-params package-dimensions" name="cargo[packages]['+ nextId +'][height]" data-package-id="'+ nextId +'"  data-dimension-type="height" placeholder="Высота" value="0.1">' +
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
            '<span class="badge calc_badge"><i class="fa fa-plus"></i></span>' +
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

    $(document).on('focus', '.package-params, #total-weight, #total-volume, #total-quantity', function () {
        $(this).val('');
    })
});

let getAllCalculatedDataAjax = false;

function getAllCalculatedData() {
    if(getAllCalculatedDataAjax) {
        getAllCalculatedDataAjax.abort();
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    getAllCalculatedDataAjax = $.ajax({
        type: 'post',
        url: '/api/calculator/get-all-calculated',
        data: $('.calculator-form').serialize(),
        dataType: "json",
        cache: false,
        beforeSend: function() {
            $('#calculator-data-preloader').show()
        },
        success: function (data) {
            renderCalendar(data);
            $('#calculator-data-preloader').hide()
        },
        error: function(data){
            $('#calculator-data-preloader').hide()
        }
    });
}

function totalVolumeRecount() {
    let totalVolume = 0;
    $.each($(".package-volume"), function(index, item) {
        var curAmount = parseFloat($(item).prev('.input-group').find( ".package-quantity" ).val());
        if(isNaN(curAmount)){curAmount = 0;}

        var curVolume = parseFloat($(item).val().replace(',', '.').toString()) * curAmount;
        totalVolume += curVolume;
    });

    $("#total-volume").attr('value', totalVolume).val(totalVolume);
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

async function checkAddressInPolygon(address, polygon) {
    if(!address.length) {
        return false;
    }

    return await ymaps.geocode(address).then(function (res) {
        var newPoint = res.geoObjects.get(0);
        map.geoObjects.removeAll().add(polygon);
        map.geoObjects.add(newPoint);

        return !!polygon.geometry.contains(newPoint.geometry.getCoordinates());
    });
}

function createPolygon(coordinates) {
    let findCoordinates = [];
    let polygonCoordinates = coordinates.match(/\[\d+\.\d+\,\s*\d+\.\d+\]/g);
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

    return new ymaps.Polygon([findCoordinates]);
}

async function getDistanceOutsidePolygon(pointStart, address, polygon) {
    let distance = 0;

    await ymaps.route([pointStart, address], {mapStateAutoApply: true})
        .then(function (route) {
            let pathsObjects = ymaps.geoQuery(route.getPaths()),
                edges = [];

            pathsObjects.each(function (path) {
                let coordinates = path.geometry.getCoordinates();
                for (let i = 1, l = coordinates.length; i < l; i++) {
                    edges.push({
                        type: 'LineString',
                        coordinates: [coordinates[i], coordinates[i - 1]]
                    });
                }
            });


            let routeObjects = ymaps.geoQuery(edges)
                .setOptions('strokeWidth', 3)
                .addToMap(map);

            map.geoObjects.add(polygon);
            let objectsInside = routeObjects.searchInside(polygon);

            let boundaryObjects = routeObjects.searchIntersect(polygon);

            objectsInside.setOptions({
                strokeColor: '#06ff00',
                //strokeColor: '#ff0005',
                prouteet: 'twirl#greenIcon'
            });

            routeObjects.remove(objectsInside).setOptions({
                strokeColor: '#0010ff',
                prouteet: 'twirl#blueIcon'
            }).each(function (item) {
                distance += item.geometry.getDistance();
            });
        }
    );

    return Math.ceil(distance / 1000);
}

// Базовая функция просчета цены для "Забрать из" и "Доставить"
function calcTariffPrice(city, point, inCity) {
    let fullName = point.data('fullName');
    if(inCity || typeof fullName === undefined || fullName === "") { // Если работаем в пределах города
        point.closest('.delivery-block').find('input.delivery-type').filter('[value="in"]').prop('checked', true);
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
                city: city.value
            },
            dataType: "json",
            cache: false,
            success: async function(data) {
                let isInPolygon;
                let polygonId = '';

                for (const el of data) {
                    let address = $("#" + point.attr('id')).val();
                    let polygon =  createPolygon(el.coordinates);

                    isInPolygon = await checkAddressInPolygon(address, polygon);
                    if(isInPolygon) {
                        polygonId = el.id;
                        break;
                    }
                }

                let hiddenPolygonInputClass = '.take-polygon-hidden-input';
                if(point.attr('id') === 'dest_point') {
                    hiddenPolygonInputClass = '.bring-polygon-hidden-input';
                }
                let hiddenPolygonInput = $(hiddenPolygonInputClass);
                hiddenPolygonInput.val(null);

                if(isInPolygon) {
                    hiddenPolygonInput.val(polygonId);
                } else if(data.length && !isInPolygon && fullName.length) {
                    if(city.point !== undefined) {
                        let el = data.pop();
                        let polygon = createPolygon(el.coordinates);

                        // console.log(city);
                        let distance = await getDistanceOutsidePolygon(city.value, fullName, polygon);
                        $(point.closest('.delivery-block')).find('.distance-hidden-input').val(distance);

                        getAllCalculatedData();
                    } else {
                        getAllCalculatedData();
                    }
                }

                getAllCalculatedData();
            },
            error: function(data){
                // console.log(data);
            }
        });
    } else { // Если работаем за пределами города
        if (!fullName) {
            getAllCalculatedData();
            return;
        }

        if (city) {
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
                    city: city.value
                },
                dataType: "json",
                cache: false,
                success: async function(data) {
                    let isInPolygon;
                    let polygonId = '';

                    for (const el of data) {
                        let address = $("#" + point.attr('id')).val();
                        let polygon =  createPolygon(el.coordinates);

                        isInPolygon = await checkAddressInPolygon(address, polygon);
                        if(isInPolygon) {
                            polygonId = el.id;
                            break;
                        }
                    }

                    let hiddenPolygonInputClass = '.take-polygon-hidden-input';
                    if(point.attr('id') === 'dest_point') {
                        hiddenPolygonInputClass = '.bring-polygon-hidden-input';
                    }
                    let hiddenPolygonInput = $(hiddenPolygonInputClass);
                    hiddenPolygonInput.val(null);

                    if(isInPolygon) {
                        hiddenPolygonInput.val(polygonId);
                        getAllCalculatedData();
                    } else if(data.length && !isInPolygon) {
                        if(city.point !== undefined) {
                            let el = data.pop();
                            let polygon = createPolygon(el.coordinates);

                            // console.log(city);
                            let distance = await getDistanceOutsidePolygon(city.value, fullName, polygon);
                            $(point.closest('.delivery-block')).find('.distance-hidden-input').val(distance);

                            getAllCalculatedData();
                        } else {
                            getAllCalculatedData();
                        }
                    } else {
                        if(city.point !== undefined) {
                            ymaps.route([city.point, fullName]).then(function (route) {
                                // console.log('От: ' + city.point + ' До: ' + fullName + ' Дистанция: ' + Math.ceil(route.getLength() / 1000));
                                $(point.closest('.delivery-block')).find('.distance-hidden-input').val(Math.ceil(route.getLength() / 1000));

                                getAllCalculatedData();
                            });
                        } else {
                            getAllCalculatedData();
                        }
                    }
                },
                error: function(data){
                    // console.log(data);
                }
            });
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
    } else if(data.error === 'Cities not found') {
        let cityFrom = $('#ship_city option:selected').val() ? $('#ship_city option:selected').val() : '<span title="Выберите город отправления">?</span>';
        let cityTo = $('#dest_city option:selected').val() ? $('#dest_city option:selected').val() : '<span title="Выберите город назначения">?</span>';

        let routeName = cityFrom + ' → ' + cityTo;

        $('#route-name').html(routeName);
        $('#base-price').html('договорная');

        drawDelivery({'take': null, 'bring': null});
        drawServices([]);
        drawDiscount(false);

        $('#total-price').html('договорная');
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
            'Забор груза: ' + (delivery.take.city_name ? delivery.take.city_name : '') + (delivery.take.polygon_name === undefined ? "" : (" (" + delivery.take.polygon_name) + ")")
            +
            (typeof delivery.take.distance !== "undefined" && delivery.take.distance !== 0 ? ('<small> (' + delivery.take.distance + ' км) </small>') : '')
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
            'Доставка груза: ' + (delivery.bring.city_name ? delivery.bring.city_name : '') + (delivery.bring.polygon_name === undefined ? "" : (" (" + delivery.bring.polygon_name + ")"))
            +
            (typeof delivery.bring.distance !== "undefined" && delivery.bring.distance !== 0 ? ('<small> (' + delivery.bring.distance + ' км) </small>') : '')
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
        if(item.name == 'Страховка'){
            $('#insurance').attr('checked', true);
        }
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

function clearDeliveryData(type) {
    let pointType = type === 'take' ? 'ship': 'dest';

    $('#' + pointType + '_point').val('');
    $('#' + pointType + '_point').data('name', '').removeAttr('data-name');
    $('#' + pointType + '_point').data('full-name', '').removeAttr('data-full-name');
    $('input[name="' + type + '_city_name"]').val('');
    $('input[name="' + type + '_distance"]').val('');
    $('input[name="' + type + '_polygon"]').val('');
}
