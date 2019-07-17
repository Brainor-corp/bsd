let map;

ymaps.ready(function () {
    map = new ymaps.Map ("hiddenMap", {
        center: [55.76, 37.64],
        zoom: 7
    });
});

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

        console.log(lastId, nextId);

        var html =
            '<div class="row package-wrapper" id="package-wrapper-'+ nextId +'">'+
            '<div class="col-11 form-item row align-items-center package-item" id="package-'+ nextId +'" data-package-id="'+ nextId +'" style="padding-right: 0;">' +
            '<label class="col-auto calc__label"><span class="content">Габариты (м)*</span></label>' +
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
            '</div>'+
            '<a href="#" id="delete-package-btn" class=" col-1 add_anotherplace">' +
            '<span class="badge calc_badge"><i class="fa fa-minus"></i></span>' +
            '</a>'+
            '<a href="#" id="add-package-btn" class=" col-1 add_anotherplace">' +
            '<span class="badge calc_badge"><i class="fa fa-plus"></i></span>' +
            '</a>'+
            '</div>'
        ;


        $(this).parent().after(html);

        totalVolumeRecount();
        totalWeigthRecount();
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

        calcTariffPrice(
            {
                'value': $('#ship_city').val(),
                'points': $('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()].terminal
            },
            $('#ship_point'),
            $('input[name="need-to-take-type"]:checked').val() == "in"
        );
    });

    $(document).on('change', 'input[name="need-to-take-type"]', function () {
        if($('input[name="need-to-take-type"]:checked').val() === 'from') {
            $('.need-to-take-input-address').removeAttr('disabled');
        } else if($('input[name="need-to-take-type"]:checked').val() === 'in') {
            $('.need-to-take-input-address').attr('disabled', 'disabled');
        }

        calcTariffPrice(
            {
                'value': $('#ship_city').val(),
                'point': $('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()].terminal
            },
            $('#ship_point'), $('input[name="need-to-take-type"]:checked').val() == "in"
        );
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
            if(point.attr('id') === 'ship_point'){
                city = $('#ship_city').text();
                cityKladrId = $('#ship_city').data().selectize.options[$('#ship_city').data().selectize.getValue()].kladrId;
            }
            if(point.attr('id') === 'dest_point') {
                city = $('#dest_city').text();
                cityKladrId = $('#dest_city').data().selectize.options[$('#dest_city').data().selectize.getValue()].kladrId;
            }
            console.log(cityKladrId);
            $(element).kladr({
                // type: $.kladr.type.city, // берем город
                oneString: true, // Если включить эту штуку, то будет возвращаться полный адрес
                parentType: $.kladr.type.city,
                parentId: cityKladrId,
                select: function (obj) {
                    kladrChange(obj, point);
                }
            });
        });
    }

    $(document).on('change', '#ship-from-point', function () {
        getAllCalculatedData();
    });

    $(document).on('change', '#bring-to-point', function () {
        getAllCalculatedData();
    });

    function updateRequiredInputs() {
        console.log('update req..');

        $('.req:hidden').removeAttr('required');
        $('.req:visible').attr('required', 'required');
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

            console.log(ui.item);
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
                        address_street: el['legal_address_street'],
                        address_house: el['legal_address_house'],
                        address_block: el['legal_address_block'],
                        address_building: el['legal_address_building'],
                        address_apartment: el['legal_address_apartment'],
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
            currentBlock.find("input[name$='address_street']").val(ui.item["address_street"]);
            currentBlock.find("input[name$='address_house']").val(ui.item["address_house"]);
            currentBlock.find("input[name$='address_block']").val(ui.item["address_block"]);
            currentBlock.find("input[name$='address_building']").val(ui.item["address_building"]);
            currentBlock.find("input[name$='address_apartment']").val(ui.item["address_apartment"]);
            currentBlock.find("input[name$='inn']").val(ui.item["inn"]);
            currentBlock.find("input[name$='kpp']").val(ui.item["kpp"]);
        }
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
        },
        success: function (data) {
            console.log(data);
            renderCalendar(data);
        },
        error: function(data){
            console.log(data);
        }
    });
}

function totalVolumeRecount() {
    let totalVolume = 0;
    $.each($(".package-volume"), function(index, item) {
        var curAmount = parseFloat($(item).prev('.input-group').find( ".package-quantity" ).val());
        if(isNaN(curAmount)){curAmount = 1;}

        var curVolume = parseFloat($(item).val().replace(',', '.').toString()) * curAmount;
        totalVolume += curVolume;
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

async function checkAddressInPolygon(address, polygon) {
    console.log('checking contains..');
    return await ymaps.geocode(address).then(function (res) {
        console.log('here!');
        var newPoint = res.geoObjects.get(0);
        map.geoObjects.removeAll().add(polygon);
        map.geoObjects.add(newPoint);

        let result = !!polygon.geometry.contains(newPoint.geometry.getCoordinates());
        console.log(result ? "В функции +" : "В функции -");

        return result;
    });
}

// Базовая функция просчета цены для "Забрать из" и "Доставить"
function calcTariffPrice(city, point, inCity) {
    if(inCity) { // Если работаем в пределах города
        getAllCalculatedData();
    } else { // В противном случае просчитываем километраж с помощью Яндекс api
        let fullName = point.data('fullName');
        if (!fullName)
            return;

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
                    city_id: city.value
                },
                dataType: "json",
                cache: false,
                success: async function(data) {
                    let isInPolygon;
                    let polygonId = '';

                    for (const el of data) {
                        console.log(el);
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

                        console.log(point.attr('id'));
                        let address = $("#" + point.attr('id')).val();
                        let polygon =  new ymaps.Polygon([findCoordinates]);

                        isInPolygon = await checkAddressInPolygon(address, polygon);
                        console.log(typeof isInPolygon);
                        if(isInPolygon) {
                            console.log(address + " содержится в " + el.name);
                            polygonId = el.id;
                            break;
                        }
                    }

                    console.log('done');

                    if(isInPolygon) {
                        console.log('Нужно поставить цену тарифа');

                        let hiddenPolygonInputClass = '.take-polygon-hidden-input';
                        if(point.attr('id') === 'dest_point') {
                            hiddenPolygonInputClass = '.bring-polygon-hidden-input';
                        }

                        let hiddenPolygonInput = $(hiddenPolygonInputClass);
                        hiddenPolygonInput.val(polygonId);

                        getAllCalculatedData();
                    } else {
                        console.log('Не нужно ставить цену тарифа');
                        ymaps.route([city.point, fullName]).then(function (route) {
                            console.log('От: ' + city.point + ' До: ' + fullName + ' Дистанция: ' + Math.ceil(route.getLength() / 1000));
                            $(point.closest('.delivery-block')).find('.distance-hidden-input').val(Math.ceil(route.getLength() / 1000));

                            getAllCalculatedData();
                        });
                    }
                },
                error: function(data){
                    console.log(data);
                }
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
}

function renderCalendar(data) {
    console.log('render');
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
            'Забор груза: ' + delivery.take.city_name + (delivery.take.polygon_name === undefined ? "" : (" (" + delivery.take.polygon_name) + ")")
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
            'Доставка груза: ' + delivery.bring.city_name + (delivery.bring.polygon_name === undefined ? "" : (" (" + delivery.bring.polygon_name + ")"))
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
