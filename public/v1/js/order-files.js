$(document).ready(function () {
    $('.ajax-file-upload').on('click', function (e) {
        e.preventDefault();

        let fileInput = $(this).next();
        $(fileInput).trigger('click');
    });

    $("#take-file-input").on('change', function () {
        let file = this.files[0];
        let formData = new FormData();

        $(this).val('');
        formData.append("file", file);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/order-file-upload',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#take-file-info').html("Загрузка..");
            },
            success: function(response) {
                $('input[name="take_driving_directions_file"]').val(response.data.path);
                $('#take-file-info').html(
                    "<a href=" + response.data.url + " target='_blank'>Файл</a>" +
                    "<a href='#' class='remove-file text-muted ml-2' data-type='take'><small>(Удалить)</small></a>"
                );
            },
            error: function (response) {
                if(response.status === 422) {
                    $('#take-file-info').html("<span class='text-danger'>" + response.responseJSON.errors['file'][0] + "</span>");
                } else {
                    $('#take-file-info').html("<span class='text-danger'>При загрузке файла произошла ошибка. Пожалуйста, попробуйте снова.</span>");
                }
            }
        });
    });

    $("#delivery-file-input").on('change', function () {
        let file = this.files[0];
        let formData = new FormData();

        $(this).val('');
        formData.append("file", file);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/order-file-upload',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#delivery-file-info').html("Загрузка..");
            },
            success: function(response) {
                $('input[name="delivery_driving_directions_file"]').val(response.data.path);
                $('#delivery-file-info').html(
                    "<a href=" + response.data.url + " target='_blank'>Файл</a>" +
                    "<a href='#' class='remove-file text-muted ml-2' data-type='delivery'><small>(Удалить)</small></a>"
                );
            },
            error: function (response) {
                if(response.status === 422) {
                    $('#delivery-file-info').html("<span class='text-danger'>" + response.responseJSON.errors['file'][0] + "</span>");
                } else {
                    $('#delivery-file-info').html("<span class='text-danger'>При загрузке файла произошла ошибка. Пожалуйста, попробуйте снова.</span>");
                }
            }
        });
    });

    $(document).on('click', '.remove-file', function (e) {
        e.preventDefault();

        let type = $(this).data('type');
        let inputName = type + "_driving_directions_file";
        let infoBlockId = "#" + type + "-file-info";

        $('input[name="' + inputName + '"]').val(null);
        $(infoBlockId).html('');
    })
});
