$(document).ready(function () {
    function errorShow(error) {
        $('.resend-alert').html(error);
        $('.resend-alert').removeClass('alert-success');
        $('.resend-alert').removeClass('alert-warning');
        $('.resend-alert').addClass('alert-danger');
        $('.resend-alert').show();
        $('.resend-button').removeAttr('disabled');
    }

    function successShow(response) {
        $('.resend-alert').html(response);
        $('.resend-alert').removeClass('alert-danger');
        $('.resend-alert').removeClass('alert-warning');
        $('.resend-alert').addClass('alert-success');
        $('.resend-alert').show();
        $('.resend-button').removeAttr('disabled');
    }

    function beforeSend() {
        $('.resend-alert').show();
        $('.resend-alert').html('Ждите..');
        $('.resend-alert').removeClass('alert-success');
        $('.resend-alert').removeClass('alert-danger');
        $('.resend-alert').addClass('alert-warning');
        $('.resend-button').attr('disabled', 'disabled');
    }

    let resendEmailHandle = function () {
        let orderId = $(this).data('order-id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/admin/orders/resend/admin-email',
            data: {
                order_id: orderId
            },
            cache: false,
            beforeSend: function() {
                beforeSend();
            },
            success: function(response){
                successShow(response);
            },
            error: function (error) {
                errorShow(error.responseText);
            }
        });
    };
    $('#resend-email').unbind('click');
    $('#resend-email').bind('click', resendEmailHandle);

    let resend1cHandle = function () {
        let orderId = $(this).data('order-id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/admin/orders/resend/order-to-1c',
            data: {
                order_id: orderId
            },
            cache: false,
            beforeSend: function() {
                beforeSend();
            },
            success: function(response){
                successShow(response);
            },
            error: function (error) {
                errorShow(error.responseText);
            }
        });
    };
    $('#resend-1c').unbind('click');
    $('#resend-1c').bind('click', resend1cHandle);

    let resendThisEmailHandle = function () {
        let orderId = $(this).data('order-id');
        let email = $('#email').val();

        if(!email.length) {
            errorShow("Введите корректный EMail");
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/admin/orders/resend/order-to-email',
            data: {
                order_id: orderId,
                email: email
            },
            cache: false,
            beforeSend: function() {
                beforeSend();
            },
            success: function(response){
                successShow(response);
            },
            error: function (error) {
                errorShow(error.responseText);
            }
        });
    };
    $('#resend-this-email').unbind('click');
    $('#resend-this-email').bind('click', resendThisEmailHandle);
});
