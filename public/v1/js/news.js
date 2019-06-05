$(document).ready(function () {
    init();

    $(document).on('click', '.delete-tag', function (e) {
        e.preventDefault();
        $(this).parent().remove();
        let type = $(this).data('type');

        if (type === 'date_select') {
            $('input[name="daterange"]').val('');
        } else {
            let valuesArray = [];
            $.each($('#tag-label-wrapper').find('[data-type=' + type + ']'), function () {
                valuesArray.push($(this).data('value'));
            });

            $("#" + type).selectpicker('val', valuesArray);
        }
        sendForm();
    });

});

var currentRequest = null;
function sendForm() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    currentRequest = $.ajax({
        type: 'post',
        url: '/news-filter',
        data: $('#filter-form').serialize(),
        cache: false,
        beforeSend : function()    {
            if(currentRequest != null) {
                currentRequest.abort();
            }
        },
        success: function (data) {
            $('#filter-wrapper').html(data);
            init();
        },
        error: function (data) {
            // console.log(data);
        }
    });

}

function init() {
    const dateRange = $('input[name="daterange"]');

    dateRange.daterangepicker({
        "locale": {
            "format": "DD.MM.YYYY",
            "separator": " - ",
            "applyLabel": "Применить",
            "cancelLabel": "Очистить",
            "fromLabel": "С",
            "toLabel": "По",
            "customRangeLabel": "Custom",
            "weekLabel": "Н",
            "daysOfWeek": [
                "Вс",
                "Пн",
                "Вт",
                "Ср",
                "Чт",
                "Пт",
                "Сб"
            ],
            "monthNames": [
                "Январь",
                "Февраль",
                "Март",
                "Апрель",
                "Май",
                "Июнь",
                "Июль",
                "Август",
                "Сентябрь",
                "Октябрь",
                "Ноябрь",
                "Декабрь"
            ],
            "firstDay": 1
        },
        opens: 'left',
        autoUpdateInput: false,
        autoApply: true,
    }, function (start, end, label) {
    });

    dateRange.on("apply.daterangepicker", function (e, picker) {
        picker.element.val(picker.startDate.format(picker.locale.format) + ' - ' + picker.endDate.format(picker.locale.format));
        sendForm();
    });

    dateRange.on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    $('#category_select').selectpicker();
    $('#city_select').selectpicker();

    $('#category_select').on('changed.bs.select', function (e) {
        if ($(this).val() != $(this).data('value')) {
            sendForm();
        }
        $(this).data('value', $(this).val());
    });

    $('#city_select').on('changed.bs.select', function (e) {
        if ($(this).val() != $(this).data('value')) {
            sendForm();
        }
        $(this).data('value', $(this).val());
    });

}