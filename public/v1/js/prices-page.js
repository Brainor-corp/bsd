let destSelectParams = {
    maxItems: 5,
    plugins: ['remove_button'],
    onChange: function (value) {
        if(value.length > 1 && value[value.length - 1] === '0') {
            $('#dest_city').selectize()[0].selectize.setValue(0);
        } else if(value.length > 1) {
            let allIndex = value.indexOf('0');
            if (allIndex > -1) {
                value.splice(allIndex, 1);
                $('#dest_city').selectize()[0].selectize.setValue(value);
                $('#dest_city').trigger('change');
            }
        }
    }
};

$(document).ready(function () {
    $('#ship_city').selectize({
        maxItems: 5,
        plugins: ['remove_button'],
        onChange: function(value) {// при изменении города отправления
            if (!value.length) return;

            if(value.length > 1 && value[value.length - 1] === '0') {
                $('#ship_city').selectize()[0].selectize.setValue(0);
                $('#ship_city').selectize()[0].selectize.updateOption('maxItems', 1);
                $('#ship_city').trigger('change');
                return;
            } else if(value.length > 1) {
                let allIndex = value.indexOf('0');
                if (allIndex > -1) {
                    value.splice(allIndex, 1);
                    $('#ship_city').selectize()[0].selectize.setValue(value);
                    $('#ship_city').trigger('change');
                }
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/api/prices/get-destination-cities',
                data: {ship_city:value},
                cache: false,
                beforeSend: function() {

                },
                success: function(html){
                    $('#dest_city').selectize()[0].selectize.destroy();
                    $('#dest_city').html(html);
                    $('#dest_city').selectize(destSelectParams);
                }
            });
        }
    });

    $('#dest_city').selectize(destSelectParams);
});
