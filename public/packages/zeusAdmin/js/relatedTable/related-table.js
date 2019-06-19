$(document).ready(function () {
    $(document).on('click', '.related-table .remove-related-row', function () {
        $(this).closest('tr').remove();
    });

    $(document).on('click', '.related-table .add-related-row', function () {
        let table = $(this).closest('table.related-table');
        let currentIndex = parseInt(table.data('current-index'));
        let patternRow = table.find('tr.pattern-row');

        patternRow = patternRow.clone().removeClass('d-none');
        patternRow = patternRow.prop("outerHTML").replace(/@pattern@/g, currentIndex);
        patternRow = patternRow.replace(/data-name/g, 'name');
        patternRow = patternRow.replace(/data-required/g, 'required');

        table.find('tbody').append(patternRow);
        table.data('current-index', currentIndex + 1);
    });
});