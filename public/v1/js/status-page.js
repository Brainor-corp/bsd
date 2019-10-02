$(document).ready(function () {
    $("input.autocomplete").autocomplete({
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
});