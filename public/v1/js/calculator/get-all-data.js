let getAllCalculatedDataAjax = false;

function renderSummary(data) {
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

export function getAllCalculatedData() {
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
            renderSummary(data);
            $('#calculator-data-preloader').hide()
        },
        error: function(data){
            $('#calculator-data-preloader').hide()
        }
    });
}
