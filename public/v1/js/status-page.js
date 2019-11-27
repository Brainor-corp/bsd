$(document).ready(function () {
    $("input[name='query']").autocomplete({
        source: function (request, response) {
            if($('select[name="type"] option:selected').val() === "cargo_number") {
                jQuery.get($(this.element).data('source'), {
                    term: request.term,
                }, function (data) {
                    let result = [];
                    $(data).each(function (key, el) {
                        result.push(el['cargo_number']);
                    });
                    response(result);
                });
            }
        },
        select: function( event, ui ) {
            event.preventDefault();

            $(event.target).val(ui.item.value);
        }
    });

    $(document).on('change', "select[name='type']", function () {
        let select = $(this);

        if(select.find('option:selected').val() === "id") {
            $("input[name='query']").attr('placeholder', 'Введите номер (напр.: 123)');
        } else {
            $("input[name='query']").attr('placeholder', 'Введите номер (напр.: СП-00000)');
        }
    })
});