$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

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
            data: {
                order_id: link.data('order-id')
            },
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
    $(document).on('click', '.show-order-documents', function (e) {
        e.preventDefault();

        let button = $(this);
        let table = $('.reports-table');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: table.data('documents-modal-url'),
            data: {
                order_id: button.data('order-id'),
                type: button.data('type')
            },
            cache: false,
            beforeSend: function() {
                $('#orderDocumentsModal .documents-container').html("Загрузка..");
                $('#orderDocumentsModal').modal();
            },
            success: function (html) {
                $('#orderDocumentsModal .documents-container').html(html);
            },
            error: function (err) {
                $('#orderDocumentsModal .documents-container')
                    .html("Произошла ошибка. Пожалуйста, обновите страницу и попробуйте снова.");
            }
        });
    });
});
