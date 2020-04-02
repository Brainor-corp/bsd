<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Helpers\LandingPagesHelper;
use App\LandingPage;
use App\Mail\SendLandingMail;
use App\Point;
use App\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Zeus\Admin\Cms\Helpers\CMSHelper;

class LandingPagesController extends Controller
{
    public function show($url)
    {
        // Кеширование на 30 дней
        return Cache::remember($url, 60 * 60 * 24 * 30, function() use ($url) {
            $landingPage = LandingPage::where('url', $url)->firstOrFail();
            $route = $landingPage->route;

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

            $args = ['type' => 'page', 'slug' => 'glavnaya-o-kompanii'];
            $aboutPage = CMSHelper::getQueryBuilder($args)->first();

            $args = ['type' => 'page', 'slug' => 'glavnaya-tekstovyy-blok'];
            $textBlock = CMSHelper::getQueryBuilder($args)->first();

            return View::make("v1.pages.landings.$landingPage->template.index")
                ->with(compact(
                    'currentShipCity',
                    'currentDestCity',
                    'shipCities',
                    'destinationCities',
                    'landingPage',
                    'route',
                    'aboutPage',
                    'textBlock'
                ))->render();
        });
    }

    public function sendMail(Request $request)
    {
        Mail::to("zakaz@123789.ru")->send(new SendLandingMail($request->get('phone')));

        return redirect()->back();
    }

    public function generateAll()
    {
        $routes = Route::where('show_in_price', true)->get();

        foreach($routes as $route) {
            $landingPage = new LandingPage();
            $landingPage->title = $route->name;
            $landingPage->route_id = $route->id;
            $landingPage->template = 'default';
            $landingPage->url = '.';
            $landingPage->save();

            $landingPage->url = $landingPage->slug;
            $landingPage->text_1 = LandingPagesHelper::generateText($landingPage, 1);
            $landingPage->text_2 = LandingPagesHelper::generateText($landingPage, 2);
            $landingPage->save();
        }

        return 'ok';
    }
}
