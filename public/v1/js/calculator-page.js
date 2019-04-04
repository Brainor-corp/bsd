$(document).ready(function () {
    $(document).on('click', '#add-package-btn', function (e) {
        e.preventDefault();
        var lastId = $( '.package-item' ).filter( ':last' ).data('packageId');
        var nextId = lastId+1;
        var html =
                '<div id="package-'+ nextId +'" class="package-item" data-package-id="'+ nextId +'">'+
                        '<div class="form-item row align-items-center">'+
                        '<label class="col-auto calc__label">Наименование груза*</label>'+
                    '<div class="col">'+
                        '<input type="text" class="form-control" placeholder="Шкаф" name="packages['+ nextId +'][name]">'+
                '</div>'+
                    '</div>'+
                    // '<div class="form-item row align-items-center">'+
                    //     '<label class="col-auto calc__label">Тип груза*</label>'+
                    //     '<div class="col">'+
                    //         '<div class="relative">'+
                    //             '<i class="dropdown-toggle fa-icon"></i>'+
                    //             '<select class="custom-select package-params" name="packages['+ nextId +'][type]">'+
                    //                 '<option disabled selected>Выберите из списка</option>'+
                    //                 '<option>1</option>'+
                    //                 '<option>2</option>'+
                    //                 '<option>3</option>'+
                    //                 '<option>4</option>'+
                    //                 '<option>5</option>'+
                    //             '</select>'+
                    //         '</div>'+
                    //     '</div>'+
                    // '</div>'+
                    '<div class="row">'+
                        '<div class="col-8">'+
                        '<div class="form-item row align-items-center">'+
                        '<label class="col-auto calc__label">Габариты (м)*</label>'+
                        '<div class="col calc__inpgrp relative row__inf">'+
                        '<div class="input-group">'+
                        '<input type="text" id="packages_'+ nextId +'_length" class="form-control text-center package-params package-dimensions" name="packages['+ nextId +'][length]" data-package-id="'+ nextId +'" data-dimension-type="length" placeholder="Д"/>'+
                '<input type="text" id="packages_'+ nextId +'_width" class="form-control text-center package-params package-dimensions" name="packages['+ nextId +'][width]" data-package-id="'+ nextId +'" data-dimension-type="width" placeholder="Ш">'+
                '<input type="text" id="packages_'+ nextId +'_height" class="form-control text-center package-params package-dimensions" name="packages['+ nextId +'][height]" data-package-id="'+ nextId +'" data-dimension-type="height" placeholder="В"/>'+
                '</div>'+
                    '</div>'+
                    '</div>'+
                    '<div class="form-item row align-items-center">'+
                        '<label class="col-auto calc__label">Вес груза (кг)*</label>'+
                    '<div class="col calc__inpgrp"><input type="text" id="packages_'+ nextId +'_weight" class="form-control package-params package-weight" name="packages['+ nextId +'][weight]" data-package-id="'+ nextId +'" data-dimension-type="weight"/></div>'+
                    '</div>'+
                    '<div class="form-item row align-items-center">'+
                        '<label class="col-auto calc__label">Объем (м<sup>3</sup>)*</label>'+
                    '<div class="col calc__inpgrp"><input type="text" id="packages_'+ nextId +'_volume" class="form-control package-params package-volume" name="packages['+ nextId +'][volume]" data-package-id="'+ nextId +'" data-dimension-type="volume"/></div>'+
                    '</div>'+
                    '</div>'+
                    '<div class="col-4">'+
                        '<p class="calc__info">Габариты груза влияют на расчет стоимости, без их указания стоимость может быть неточной</p>'+
                    '</div>'+
                    '</div>'+
                    '</div>'
        ;
        $(this).before(html);
    });
});