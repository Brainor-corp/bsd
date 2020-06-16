import map from "./map.js";

import {getAllCalculatedData} from "./get-all-data.js";
import {checkAddressInPolygon} from "./ckeck-address-in-polygon.js";

function createPolygon(coordinates) {
    let findCoordinates = [];
    let polygonCoordinates = coordinates.match(/\[\d+\.\d+\,\s*\d+\.\d+\]/g);
    $(polygonCoordinates).each(function (pairKey, pairVal) {
        pairVal = pairVal.replace(' ', '');
        pairVal = pairVal.replace('[', '');
        pairVal = pairVal.replace(']', '');
        let parts = pairVal.split(',');
        findCoordinates.push([
            parseFloat(parts[0]),
            parseFloat(parts[1])
        ]);
    });

    return new ymaps.Polygon([findCoordinates]);
}

async function getDistanceOutsidePolygon(pointStart, address, polygon) {
    let distance = 0;

    await ymaps.route([pointStart, address], {mapStateAutoApply: true})
        .then(function (route) {
                let pathsObjects = ymaps.geoQuery(route.getPaths()),
                    edges = [];

                pathsObjects.each(function (path) {
                    let coordinates = path.geometry.getCoordinates();
                    for (let i = 1, l = coordinates.length; i < l; i++) {
                        edges.push({
                            type: 'LineString',
                            coordinates: [coordinates[i], coordinates[i - 1]]
                        });
                    }
                });


                let routeObjects = ymaps.geoQuery(edges)
                    .setOptions('strokeWidth', 3)
                    .addToMap(map);

                map.geoObjects.add(polygon);
                let objectsInside = routeObjects.searchInside(polygon);

                let boundaryObjects = routeObjects.searchIntersect(polygon);

                objectsInside.setOptions({
                    strokeColor: '#06ff00',
                    //strokeColor: '#ff0005',
                    prouteet: 'twirl#greenIcon'
                });

                routeObjects.remove(objectsInside).setOptions({
                    strokeColor: '#0010ff',
                    prouteet: 'twirl#blueIcon'
                }).each(function (item) {
                    distance += item.geometry.getDistance();
                });
            }
        );

    return Math.ceil(distance / 1000);
}

// Базовая функция просчета цены для "Забрать из" и "Доставить"
export function calcTariffPrice(city, point, inCity) {
    let fullName = point.data('fullName');
    if(inCity || typeof fullName === undefined || fullName === "") { // Если работаем в пределах города
        point.closest('.delivery-block').find('input.delivery-type').filter('[value="in"]').prop('checked', true);
        // Пробуем получить полигоны для выбранного города
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/api/calculator/get-city-polygons',
            data: {
                city: city.value
            },
            dataType: "json",
            cache: false,
            success: async function(data) {
                let isInPolygon;
                let polygonId = '';

                for (const el of data) {
                    let address = $("#" + point.attr('id')).val();
                    let polygon =  createPolygon(el.coordinates);

                    isInPolygon = await checkAddressInPolygon(address, polygon);
                    if(isInPolygon) {
                        polygonId = el.id;
                        break;
                    }
                }

                let hiddenPolygonInputClass = '.take-polygon-hidden-input';
                if(point.attr('id') === 'dest_point') {
                    hiddenPolygonInputClass = '.bring-polygon-hidden-input';
                }
                let hiddenPolygonInput = $(hiddenPolygonInputClass);
                hiddenPolygonInput.val(null);

                if(isInPolygon) {
                    hiddenPolygonInput.val(polygonId);
                } else if(data.length && !isInPolygon && fullName.length) {
                    if(city.point !== undefined) {
                        let el = data.pop();
                        let polygon = createPolygon(el.coordinates);

                        // console.log(city);
                        let distance = await getDistanceOutsidePolygon(city.value, fullName, polygon);
                        $(point.closest('.delivery-block')).find('.distance-hidden-input').val(distance);

                        getAllCalculatedData();
                    } else {
                        getAllCalculatedData();
                    }
                }

                getAllCalculatedData();
            },
            error: function(data){
                // console.log(data);
            }
        });
    } else { // Если работаем за пределами города
        if (!fullName) {
            getAllCalculatedData();
            return;
        }

        if (city) {
            // Пробуем получить полигоны для выбранного города
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/api/calculator/get-city-polygons',
                data: {
                    city: city.value
                },
                dataType: "json",
                cache: false,
                success: async function(data) {
                    let isInPolygon;
                    let polygonId = '';

                    for (const el of data) {
                        let address = $("#" + point.attr('id')).val();
                        let polygon =  createPolygon(el.coordinates);

                        isInPolygon = await checkAddressInPolygon(address, polygon);
                        if(isInPolygon) {
                            polygonId = el.id;
                            break;
                        }
                    }

                    let hiddenPolygonInputClass = '.take-polygon-hidden-input';
                    if(point.attr('id') === 'dest_point') {
                        hiddenPolygonInputClass = '.bring-polygon-hidden-input';
                    }
                    let hiddenPolygonInput = $(hiddenPolygonInputClass);
                    hiddenPolygonInput.val(null);

                    if(isInPolygon) {
                        hiddenPolygonInput.val(polygonId);
                        getAllCalculatedData();
                    } else if(data.length && !isInPolygon) {
                        if(city.point !== undefined) {
                            let el = data.pop();
                            let polygon = createPolygon(el.coordinates);

                            // console.log(city);
                            let distance = await getDistanceOutsidePolygon(city.value, fullName, polygon);
                            $(point.closest('.delivery-block')).find('.distance-hidden-input').val(distance);

                            getAllCalculatedData();
                        } else {
                            getAllCalculatedData();
                        }
                    } else {
                        if(city.point !== undefined) {
                            ymaps.route([city.point, fullName]).then(function (route) {
                                // console.log('От: ' + city.point + ' До: ' + fullName + ' Дистанция: ' + Math.ceil(route.getLength() / 1000));
                                $(point.closest('.delivery-block')).find('.distance-hidden-input').val(Math.ceil(route.getLength() / 1000));

                                getAllCalculatedData();
                            });
                        } else {
                            getAllCalculatedData();
                        }
                    }
                },
                error: function(data){
                    // console.log(data);
                }
            });
        }
    }
}
