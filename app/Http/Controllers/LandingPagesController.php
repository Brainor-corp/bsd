<?php

namespace App\Http\Controllers;

use App\City;
use App\Point;
use App\Route;
use Illuminate\Http\Request;

class LandingPagesController extends Controller
{
    public function show()
    {
        $route = Route::find(190); // todo

        $shipCities = City::where('is_ship', true)->get();      // Города отправления
        $shipPoints = Point::has('city')->with('city')->get();  // Особые пункты отправления

        // Объединим города и пункты в одну коллекцию
        $shipCities = Collect($shipCities->pluck('name'))->merge($shipPoints->pluck('name'));
        $shipCities = $shipCities->map(function ($item, $key) {
            $city = new \stdClass();
            $city->name = trim($item);

            return $city;
        })->unique()->sortBy('name');

        // Города/пункты отправления/назначения по умолчанию
        $selectedShipCity = null;
        $selectedDestCity = null;

        $selectedShipCity = City::where('id', $route->ship_city_id)->first(); // Пробуем найти его в таблице городов по конкретному id (53 -- Москва)

        // Получим id города/пункта отправления в зависимости от того, в какой таблице он нашёлся
        $selectedShipCity = $selectedShipCity instanceof City ? $selectedShipCity->id : $selectedShipCity->city_id;

        // Выбираем города назначения
        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $selectedShipCity))
            ->orderBy('name')
            ->get();

        // Выбираем пункты назначения
        $destinationPoints = Point::whereHas('city', function ($cityQ) use ($selectedShipCity) {
            return $cityQ->whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $selectedShipCity));
        })->with('city')->get();

        // Объединим города и пункты назначения в одну коллекцию
        $destinationCities = Collect($destinationCities->pluck('name'))->merge($destinationPoints->pluck('name'));
        $destinationCities = $destinationCities->unique()->map(function ($item, $key) {
            $city = new \stdClass();
            $city->name = $item;

            return $city;
        })->sortBy('name');

        $selectedDestCity = City::where('id', $route->dest_city_id)->first(); // Пробуем найти город назначения по его id (78 -- Санкт-Петербург) в таблице городов

        // Получим id города/пункта назначения в зависимости от того, в какой таблице он нашёлся
        $selectedDestCity = $selectedDestCity instanceof City ? $selectedDestCity->id : $selectedDestCity->city_id;

        $currentShipCity = City::where('id', $selectedShipCity)->firstOrFail();
        $currentDestCity = City::where('id', $selectedDestCity)->firstOrFail();

        return view('v1.pages.landings.default.index')
            ->with(compact(
            'currentShipCity',
            'currentDestCity',
            'shipCities',
            'destinationCities',
                'route'
        ));
    }
}
