$(document).ready(function () {
    $('input[name="payment_type"]').click(function () {
        if ($(this).attr("value") == "3rd-person") {
            $("#3rd-person-payer").show('slow');
        }else{
            $("#3rd-person-payer").hide('slow');
        }
    });
});