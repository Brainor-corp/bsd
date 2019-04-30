$(document).ready(function () {

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
    }, function (start, end, label) {
    });
    dateRange.val('');

    dateRange.on("apply.daterangepicker", function (e, picker) {
        picker.element.val(picker.startDate.format(picker.locale.format) + ' - ' + picker.endDate.format(picker.locale.format));
        sendForm();
    });
    dateRange.on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    $('select').selectpicker();

    $('#category_select').change(function () {
        sendForm();

    });
    $('#city_select').change(function () {
        sendForm();
    });

    $(document).on('click', '.delete-tag', function (e) {
        e.preventDefault();
        $(this).parent().remove();
        let type = $(this).data('type');

        if (type == 'date_select') {
            dateRange.val('');
        } else {
            let valuesArray = [];
            $.each($('#tag-label-wrapper').find('[data-type=' + type + ']'), function () {
                valuesArray.push($(this).data('value'));
            });

            $("#" + type).selectpicker('val', valuesArray);
        }
    });

});

function sendForm() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/news-filter',
        data: $('#filter-form').serialize(),
        cache: false,
        success: function (data) {
            console.table(data);
            reDraw();
        },
    });

}

function deleteTags() {
    $.each($('.delete-tag'), function () {
        $(this).parent().remove();
    });
}

function reDraw() {
    deleteTags();

    if ($('input[name="daterange"]').val()) {
        let datepicker = $('input[name="daterange"]').data('daterangepicker');
        let text = 'С ' + datepicker.startDate.format('DD.MM.YYYY') + ' по ' + datepicker.endDate.format('DD.MM.YYYY');
        addTag('date_select', 0, text);
    }

    $.each($('#category_select').val(), function () {
        addTag('category_select', this, $('#category_select').find('option[value=' + this + ']').text());
    });
    $.each($('#city_select').val(), function () {
        addTag('city_select', this, $('#city_select').find('option[value=' + this + ']').text());
    });
}

function addTag(type, value, text) {
    $('#tag-label-wrapper').append('' +
        '<div class="selected-item d-flex align-items-center margin-item">\n' +
        '   <span class="selected-item__name">' + text + '</span>\n' +
        '   <a data-type="' + type + '" data-value="' + value + '" class="delete-tag" href="#"><i class="fa fa-close"></i></a>\n' +
        '</div>');
}