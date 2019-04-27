$(document).ready(function () {
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
    })
});