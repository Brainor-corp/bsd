$(document).ready(function () {
    $('#category_select').change(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/search-orders',
            data: {categories: $('#category_select').val()},
            cache: false,
            success: function (html) {
                $('#tag-label-wrapper').append('<div class="selected-item d-flex align-items-center margin-item">\n' +
                    '                                    <span class="selected-item__name">Санкт-Петербург</span>\n' +
                    '                                    <a href=""><i class="fa fa-close"></i></a>\n' +
                    '                                </div>');
            },
        });
    });
});