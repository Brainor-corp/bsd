$(document).ready(function () {
    // Textarea and select clone() bug workaround | Spencer Tipping
    // Licensed under the terms of the MIT source code license

    // Motivation.
    // jQuery's clone() method works in most cases, but it fails to copy the value of textareas and select elements. This patch replaces jQuery's clone() method with a wrapper that fills in the
    // values after the fact.

    // An interesting error case submitted by Piotr Przybył: If two <select> options had the same value, the clone() method would select the wrong one in the cloned box. The fix, suggested by Piotr
    // and implemented here, is to use the selectedIndex property on the <select> box itself rather than relying on jQuery's value-based val().
    (function (original) {
        jQuery.fn.clone = function () {
            var result           = original.apply(this, arguments),
                my_textareas     = this.find('textarea').add(this.filter('textarea')),
                result_textareas = result.find('textarea').add(result.filter('textarea')),
                my_selects       = this.find('select').add(this.filter('select')),
                result_selects   = result.find('select').add(result.filter('select'));

            for (var i = 0, l = my_textareas.length; i < l; ++i) $(result_textareas[i]).val($(my_textareas[i]).val());
            for (var i = 0, l = my_selects.length;   i < l; ++i) result_selects[i].selectedIndex = my_selects[i].selectedIndex;

            return result;
        };
    }) (jQuery.fn.clone);

    $(document).on('click', '.related-table .remove-related-row', function () {
        $(this).closest('tr').remove();
    });

    $(document).on('click', '.related-table .add-related-row', function () {
        let table = $(this).closest('table.related-table');
        let currentIndex = parseInt(table.data('current-index'));
        let patternRow = table.find('tr.pattern-row').clone(true);

        patternRow = patternRow.clone().removeClass('d-none');
        patternRow.removeClass('pattern-row');
        patternRow = patternRow.prop("outerHTML").replace(/@pattern@/g, currentIndex);
        patternRow = patternRow.replace(/data-name/g, 'name');
        patternRow = patternRow.replace(/data-required/g, 'required');

        table.find('tbody').append(patternRow);
        table.data('current-index', currentIndex + 1);

        $('.related-table .bootstrap-select').each(function (key, el) {
            let parent = $(el).parent();
            let select = $(el).find('select.selectpicker').clone();

            el.remove();
            select.appendTo(parent);
            parent.find('select.selectpicker').selectpicker();
        });
    });
});