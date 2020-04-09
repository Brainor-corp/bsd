$(document).ready(function () {
    getBalance();

    $('#update-balance-btn .update').click(function () {
        getBalance();
    });
});

function getBalance(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/profile/balance/get',
        cache: false,
        beforeSend: function() {
            $('.alert.alert-danger').addClass('d-none');
            $('.alert.alert-danger ul').empty();
            $('#update-balance-btn .update').addClass('d-none');
            $('#update-balance-btn .loading-svg').removeClass('d-none');
        },
        success: function(balance){
            $('#balance-input').val(balance);
            $('#update-balance-btn .update').removeClass('d-none');
            $('#update-balance-btn .loading-svg').addClass('d-none');
        },
        error: function (data) {
            $('.alert.alert-danger').removeClass('d-none');
            $('.alert.alert-danger ul').html('<li>' + data.responseJSON.message + '</li>');
            $('#update-balance-btn .update').removeClass('d-none');
            $('#update-balance-btn .loading-svg').addClass('d-none');
        }
    });
}
