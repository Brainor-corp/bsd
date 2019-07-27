$(document).ready(function () {
    if($('#process-order').length) {
        $('#process-order').modal('show');
    }

    $('.city-search').selectize({
        create: false,
        maxItems: 1,
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        load: function (query, callback) {
            if (!query.length) return callback();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/api/get-cities-by-term',
                type: 'POST',
                data: {
                    term: query,
                    maxresults: 10
                },
                error: function (data) {
                    console.log(data);
                    callback();
                },
                success: function (res) {
                    console.log(res);
                    callback(res);
                }
            });
        },
        render: {
            option: function(item, escape) {
                return '<div>' +
                    '<span class="title">' +
                    '<span class="name">' + escape(item.name) + '</span>' +
                    '</span>' +
                    '</div>';
            }
        },
        onChange: function(value, isOnInitialize) {
            if(value) {
                window.location.replace($('#changeCityGlobal').data('redirect').toString() + '/' + value);
            }
        }
    });

    $('.phone-mask').mask('+7 (000) 000-00-00');
    $('.sms-code-mask').mask('000000');

    let searchParams = new URLSearchParams(window.location.search);
    if(searchParams.has('cn') && $('.header__myaccount_link').hasClass('cn')) {
        $('.header__myaccount_link').dropdown('toggle')
    }
});