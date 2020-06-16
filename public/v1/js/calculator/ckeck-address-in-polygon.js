import map from "./map.js";

export async function checkAddressInPolygon(address, polygon) {
    if(!address.length) {
        return false;
    }

    return await ymaps.geocode(address).then(function (res) {
        var newPoint = res.geoObjects.get(0);
        map.geoObjects.removeAll().add(polygon);
        map.geoObjects.add(newPoint);

        return !!polygon.geometry.contains(newPoint.geometry.getCoordinates());
    });
}
