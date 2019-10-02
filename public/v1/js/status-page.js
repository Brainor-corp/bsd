$(document).ready(function () {
    $("input.autocomplete").autocomplete({
        source: function (request, response) {
            jQuery.get($(this.element).data('source'), {
                term: request.term,
            }, function (data) {
                let result = [];
                $(data).each(function (key, el) {
                    result.push(el['cargo_number']);
                });
                response(result);
            });
        },
        select: function( event, ui ) {
            event.preventDefault();

            console.log(ui.item);
            console.log($(event.target));
        }
    });
});