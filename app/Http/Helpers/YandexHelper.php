<?php

namespace App\Http\Helpers;

class YandexHelper {

    /**
     * Возвращает дистанцию в метрах между двумя точками на карте
     *
     * В случае ошибки возвращает false
     *
     * @param $pointFrom
     * @param $pointTo
     * @return int|bool
     */
//    public static function getDistance($pointFrom, $pointTo) {
//        $distance = false;
//
//        $pointFrom = str_replace(', ', ',', $pointFrom);
//        $pointFrom = str_replace(' ', ', ', $pointFrom);
//        $pointFromParts = explode(',', $pointFrom);
//        $pointFrom = "$pointFromParts[1],$pointFromParts[0]";
//
//        $pointTo = str_replace(', ', ',', $pointTo);
//        $pointTo = str_replace(' ', ', ', $pointTo);
//        $pointToParts = explode(',', $pointTo);
//        $pointTo = "$pointToParts[1],$pointToParts[0]";
//
//        $params = [
//            'origins' => $pointFrom , // '55.7538127,37.5755189',
//            'destinations' => $pointTo, // '55.7489841,37.564189',
//            'mode' => 'driving',
//            'apikey' => env('YANDEX_ROUTE_KEY'),
//        ];
//        $query = http_build_query($params);
//
//        $curl = curl_init();
//        curl_setopt_array($curl, [
//            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_URL => 'https://api.routing.yandex.net/v1.0.0/distancematrix?' . $query
//        ]);
//        $resp = curl_exec($curl);
//        curl_close($curl);
//
//        $result = json_decode($resp);
//
//        if(isset($result->rows[0]->elements[0]) && $result->rows[0]->elements[0]->status === "OK") {
//            return intval($result->rows[0]->elements[0]->distance->value / 1000);
//        }
//
//        return $distance;
//    }

    /**
     * Возвращает координаты полученного адреса
     *
     * В случае ошибки возвращает false
     *
     * @param $address
     * @return string|bool
     */
    public static function getCoordinates($address) {
        $params = [
            'geocode' => $address , // 'Волгоград, ул. 40 лет ВЛКСМ',
            'format' => 'json',
            'apikey' => env('YANDEX_GEO_KEY'),
        ];
        $query = http_build_query($params);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://geocode-maps.yandex.ru/1.x/?' . $query
        ]);
        $resp = curl_exec($curl);
        curl_close($curl);

        return json_decode($resp)->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos ?? false;
    }

}
