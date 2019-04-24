$(document).ready(function () {
    $('input[name="payer_type"]').click(function () {
        if ($(this).attr("value") == "3-e-lico") {
            $("#3rd-person-payer").show('slow');
        }else{
            $("#3rd-person-payer").hide('slow');
        }
    });
});