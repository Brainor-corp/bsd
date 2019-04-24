$(document).ready(function () {
    $('.events-feed__close').click(function () {
        let btn = $(this);
        let id = btn.data('event-id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/event-hide',
            data: {event_id: id},
            cache: false,
            success: function (response) {
                if (response === 'ok') {
                    $(btn).parent().hide('slow');
                    $(btn).parent().remove();
                }
            },
            error: function (err) {
                // console.log(err);
            }
        });
    });

    $(document).on('click', '.show-order-items', function (e) {
        e.preventDefault();
        let link = $(this);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/get-order-items',
            data: {order_id: link.data('order-id')},
            cache: false,
            success: function (html) {
                $('#orderItemsModal').find('.modal-body').html(html);

                $('#orderItemsModal').modal();
            },
            error: function (err) {
                // console.log(err);
            }
        });
    });

    $('#search-type-select').change(function () {
        let select = $(this);
        if (select.val() === 'id') {
            $('#search-input').remove();
            $('#search-select').remove();
            $('#search-wrapper').append('<input id="search-input" type="text" class="form-control search-input" placeholder="Введите номер">');
        } else if (select.val() === 'status') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/get-order-search-input',
                cache: false,
                success: function (html) {
                    $('#search-input').remove();
                    $('#search-select').remove();

                    $('#search-wrapper').append(html);
                },
                error: function (err) {
                    // console.log(err);
                }
            });
        }
    });

    $(document).on('keyup', '#search-input', function () {
        orderSearch()
    });

    $(document).on('change', '#search-select', function () {
        orderSearch()
    });
    $(document).on('change', '#finished-cb', function () {
        orderSearch()
    });
});

function orderSearch() {
    let data;
    if ($('#search-type-select').val() === 'id') {
        data = {id: $('#search-input').val(), finished: $('#finished-cb').is(':checked')};
    }
    else if ($('#search-type-select').val() === 'status') {
        data = {status: $('#search-select').val(), finished: $('#finished-cb').is(':checked')};
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/search-orders',
        data: data,
        cache: false,
        success: function (html) {
            $('#orders-table-body').html(html);
        },
        error: function (err) {
            // console.log(err);
        }
    });
}