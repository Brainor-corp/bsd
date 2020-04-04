$(document).ready(function () {
    $('#calcUserForm').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let btn = form.find('button[type="submit"]');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: form.attr('action'),
            cache: false,
            data: form.serialize(),
            beforeSend: function() {
                btn.attr('disabled', 'disabled');
                form.find('.error').hide();
            },
            success: function() {
                form.find('.modal-body').html(
                "<div class='alert alert-success'>Заявка принята. Оператор свяжется с Вами в ближайшее время.</div>" +
                "");
                form.find('.modal-footer').remove();
            },
            error: function (response) {
                grecaptcha.reset();
                btn.removeAttr('disabled');

                if(response.status === 400) {
                    if(response.responseJSON.data['g-recaptcha-response']) {
                        form.find('.error.g-recaptcha-response')
                            .text(response.responseJSON.data['g-recaptcha-response'][0])
                            .show();
                    }

                    if(response.responseJSON.data['phone']) {
                        form.find('.error.phone')
                            .text(response.responseJSON.data['phone'][0])
                            .show();
                    }
                } else {
                    form.find('.modal-body').html(
                    "<div class='alert alert-danger'>Произошла ошибка. Пожалуйста, обновите страницу и попробуйте снова.</div>" +
                    "");
                    form.find('.modal-footer').remove();
                }
            }
        });
    })
});
