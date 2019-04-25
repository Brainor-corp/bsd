<?php

namespace App\Http\Helpers;

class YandexHelper {

    /**
     * Возвращает дистанцию в метрах между двумя точками на карте
     *
     * @param $pointFrom
     * @param $pointTo
     * @return int
     */
    public static function getDistance($pointFrom, $pointTo) {
        $distance = 0;

        $pointFrom = str_replace(', ', ',', $pointFrom);
        $pointFrom = str_replace(' ', ', ', $pointFrom);

        $pointTo = str_replace(' ', ',', $pointTo);
        $pointTo = str_replace(' ', ',', $pointTo);

        $params = [
            'origins' => $pointFrom , // '55.7538127,37.5755189',
            'destinations' => $pointTo, // '55.7489841,37.564189',
            'mode' => 'transit',
            'apikey' => env('YANDEX_ROUTE_KEY'),
        ];
        $query = http_build_query($params);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.routing.yandex.net/v1.0.0/distancematrix?' . $query
        ]);
        $resp = curl_exec($curl);
        curl_close($curl);

        dd(json_decode($resp));

        return $distance;
    }

    /**
     * Возвращает координаты полученного адреса
     *
     * @param $address
     * @return string
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