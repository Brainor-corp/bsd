<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Http\Controllers\Controller;
use App\Http\Geo\Point;
use App\Http\Helpers\YandexHelper;
use App\Terminal;

class CitiesClosestTerminalUpdaterController extends Controller
{
    public function updateAction() {
        $cities = City::where('update_closest_terminal', true)->get();
        $terminals = Terminal::all();

        // Сохраним терминалы в массив вида ['терминал', 'точка терминала']
        $terminalsWithPoint = [];
        foreach($terminals as $key => $terminal) {
            $terminalCoorinates = explode(', ', $terminal->geo_point);

            if(count($terminalCoorinates) != 2) {
                continue;
            }

            $terminalsWithPoint[$key]['terminal'] = $terminal; // терминал
            $terminalsWithPoint[$key]['point'] = new Point($terminalCoorinates[0], $terminalCoorinates[1]); // точка терминала
        }

        // Идём по каждому городу
        foreach($cities as $city) {
            $cityCoordinates = YandexHelper::getCoordinates($city->name); // Определяем позицию города через Yandex Api

            // Если Yandex Api вернул ошибку, перейдём к след. городу
            if(!$cityCoordinates) {
                continue;
            }

            // Если по каким-то причинам координаты пришли в некорректном виде, перейдём к след. городу
            $cityCoordinates = explode(' ', $cityCoordinates);
            if(count($cityCoordinates) != 2) {
                continue;
            }

            $cityPoint = new Point($cityCoordinates[0], $cityCoordinates[1]);
            $curNearestPoint = $terminalsWithPoint[0]; // Изначально ближайшей точкой считаем нулевой терминал
            $curNearestDistance = $cityPoint->distanceTo($curNearestPoint['point']);    // Изначально минимальной дистанцией
                                                                                        // считаем дистанцию до нулевого терминала

            // Проходим по всем терминалам
            foreach ($terminalsWithPoint as $point) {
                $distance = $cityPoint->distanceTo($point['point']); // Находим дистанцию от города до текущего терминала
                if ($distance < $curNearestDistance) { // Если дистанция меньше той, что нашли раньше
                    $curNearestDistance = $distance; // Запомним текущую минимальную дистанцию
                    $curNearestPoint = $point; // Запомним текущий терминал
                }
            }

            // Обновим ближайший терминал города
            $city->closest_terminal_id = $curNearestPoint['terminal']->id;
            $city->update();
        }

        return redirect()->back();
    }
}
