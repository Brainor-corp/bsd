<?php

namespace App\Http\Controllers;

use App\City;
use App\CmsBoosterPost;
use App\Http\Helpers\CalculatorHelper;
use App\OversizeMarkup;
use App\Point;
use App\Route;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Zeus\Admin\Cms\Helpers\CMSHelper;

class MainPageController extends Controller
{
    public function index(Request $request) {
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

        if(isset($request->ship_city)){ // Если город отправления выбран
            $selectedShipCity = City::where('name', $request->ship_city)->first(); // Пробуем найти его в таблице городов по названию
            if(empty($selectedShipCity)) { // Если не нашли
                $selectedShipCity = Point::where('name', $request->ship_city)->firstOrFail(); // Пробуем найти его в таблице пунктов
            }
        } else { // Если город отправления не выбран
            $selectedShipCity = City::where('id', 53)->first(); // Пробуем найти его в таблице городов по конкретному id (53 -- Москва)
        }

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


        // Если город отправления и город назначения выбран
        if(isset($request->ship_city) && isset($request->dest_city)){
            $selectedDestCity = City::where('name', $request->dest_city)->first(); // Пробуем найти город назначения по его названию в таблице город
            if(empty($selectedDestCity)) { // Если не нашли
                $selectedShipCity = Point::where('name', $request->dest_city)->firstOrFail(); // Пробуем найти город по его названию в таблице пунктов
            }
        }else{
            $selectedDestCity = City::where('id', 78)->first(); // Пробуем найти город назначения по его id (78 -- Санкт-Петербург) в таблице городов
        }

        // Получим id города/пункта назначения в зависимости от того, в какой таблице он нашёлся
        $selectedDestCity = $selectedDestCity instanceof City ? $selectedDestCity->id : $selectedDestCity->city_id;

        $shipCity = City::where('id', $selectedShipCity)->firstOrFail();
        $destCity = City::where('id', $selectedDestCity)->firstOrFail();

        $packages = [];
        $weight = 1;
        $volume = 0.01;
        $quantity = 0;

        $routeData = CalculatorHelper::getRouteData(
            $shipCity,
            $destCity,
            $packages,
            $weight,
            $volume,
            $quantity
        );
        $tariff = [
            'base_price' => $routeData['price'],
            'total_volume' => $routeData['totalVolume'],
            'route' => $routeData['model'],
        ];

        $route = $routeData['model'];

        $services = Service::get();

        $currentCity = null;

        if(Session::has('current_city')) {
            $sessionCity = Session::get('current_city');
            if(isset($sessionCity['id'])) {
                $currentCity = City::where('id', $sessionCity['id'])
                    ->first();
            }
        }

        if(!isset($currentCity->closest_terminal_id)) {
            $currentCity = City::where('slug', 'sankt-peterburg')
                ->firstOrFail();
        }

        //news
        $news = CmsBoosterPost::whereHas('terminals', function ($terminalsQ) use ($currentCity) {
                $terminalsQ->whereHas('city', function ($cityQ) use ($currentCity) {
                    return $cityQ->where('id', $currentCity->id);
                });
            })
            ->where([
                ['type', 'news'],
                ['status', 'published'],
            ])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        //services
        $args = [
            'type' => 'page',
            'tags' => ['main-page-services'],
            'order_by' => ['published_at','ASC'],
        ];
        $servicesPostsChunk = CMSHelper::getQueryBuilder($args)
            ->limit(4)
            ->get()
            ->chunk(2);

        $args = [
            'type' => 'page',
            'slug' => 'glavnaya-o-kompanii'
        ];
        $aboutPage = CMSHelper::getQueryBuilder($args)
            ->first();

        $args = [
            'type' => 'page',
            'slug' => 'glavnaya-tekstovyy-blok'
        ];
        $textBlock = CMSHelper::getQueryBuilder($args)
            ->first();

        return view('v1.pages.index.index')
            ->with(compact(
                'packages',
                'shipCities',
                'destinationCities',
                'route',
                'tariff',
                'services',
                'news',
                'servicesPostsChunk',
                'aboutPage',
                'textBlock'
            ));
    }
}
