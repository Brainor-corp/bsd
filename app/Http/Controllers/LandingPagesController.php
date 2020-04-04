<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Helpers\LandingPagesHelper;
use App\Http\Helpers\TextHelper;
use App\LandingPage;
use App\Mail\SendLandingMail;
use App\Point;
use App\Route;
use App\Rules\GoogleReCaptchaV2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Zeus\Admin\Cms\Helpers\CMSHelper;
use Zeus\Admin\Cms\Models\ZeusAdminPost;

class LandingPagesController extends Controller
{
    public function show($url)
    {
        // Кеширование на 30 дней
        $cacheTime = 60 * 60 * 24 * 30;

        $landingPage = Cache::remember("$url.landingPage", $cacheTime, function() use ($url) {
            return LandingPage::where('url', $url)->with('route.shipCity', 'route.destinationCity')->firstOrFail();
        });

        $route = $landingPage->route;

        $shipCities = Cache::remember("$url.shipCities", $cacheTime, function() {
            return City::where('is_ship', true)->get();      // Города отправления
        });

        $shipPoints = Cache::remember("$url.shipPoints", $cacheTime, function() {
            return Point::has('city')->with('city')->get();  // Особые пункты отправления
        });

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

        $selectedShipCity = Cache::remember("$url.selectedShipCity", $cacheTime, function() use ($route) {
            return City::where('id', $route->ship_city_id)->first(); // Пробуем найти его в таблице городов по конкретному id (53 -- Москва)
        });

        // Получим id города/пункта отправления в зависимости от того, в какой таблице он нашёлся
        $selectedShipCity = $selectedShipCity instanceof City ? $selectedShipCity->id : $selectedShipCity->city_id;

        $destinationCities = Cache::remember("$url.destinationCities", $cacheTime, function() use ($selectedShipCity) {
            // Выбираем города назначения
            return City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $selectedShipCity))
                ->orderBy('name')
                ->get();
        });

        $destinationPoints = Cache::remember("$url.destinationPoints", $cacheTime, function() use ($selectedShipCity) {
            // Выбираем пункты назначения
            return Point::whereHas('city', function ($cityQ) use ($selectedShipCity) {
                return $cityQ->whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $selectedShipCity));
            })->with('city')->get();
        });

        $destinationCities = Cache::remember("$url.destinationCitiesMerged", $cacheTime, function() use ($destinationCities, $destinationPoints) {
            // Объединим города и пункты назначения в одну коллекцию
            return Collect($destinationCities->pluck('name'))->merge($destinationPoints->pluck('name'));
        });

        $destinationCities = $destinationCities->unique()->map(function ($item, $key) {
            $city = new \stdClass();
            $city->name = $item;

            return $city;
        })->sortBy('name');

        $selectedDestCity = Cache::remember("$url.selectedDestCity", $cacheTime, function() use ($route) {
            return City::where('id', $route->dest_city_id)->first(); // Пробуем найти город назначения по его id (78 -- Санкт-Петербург) в таблице городов
        });

        // Получим id города/пункта назначения в зависимости от того, в какой таблице он нашёлся
        $selectedDestCity = $selectedDestCity instanceof City ? $selectedDestCity->id : $selectedDestCity->city_id;

        $currentShipCity = Cache::remember("$url.currentShipCity", $cacheTime, function() use ($selectedShipCity) {
            return City::where('id', $selectedShipCity)->firstOrFail();
        });

        $currentDestCity = Cache::remember("$url.currentDestCity", $cacheTime, function() use ($selectedDestCity) {
            return City::where('id', $selectedDestCity)->firstOrFail();
        });

        $aboutPage = Cache::remember("$url.aboutPage", $cacheTime, function() {
            $args = ['type' => 'page', 'slug' => 'glavnaya-o-kompanii'];
            return CMSHelper::getQueryBuilder($args)->first();
        });

        $textBlock = Cache::remember("$url.textBlock", $cacheTime, function() {
            $args = ['type' => 'page', 'slug' => 'glavnaya-tekstovyy-blok'];
            return CMSHelper::getQueryBuilder($args)->first();
        });

        // Получение услуг не кешируется, т.к. услуги одинаковые на всех посадочных.
        // Если их кешировать, то при изменении нужно будет очищать кеш сразу у всех посадочных.
        $servicesPosts = ZeusAdminPost::whereHas('categories', function ($categoryQ) {
            return $categoryQ->where([
                ['type', 'category'],
                ['slug', 'posadochnaya-usluga'],
                ['status', 'published']
            ]);
        })->get();

        return View::make("v1.pages.landings.$landingPage->template.index")
            ->with(compact(
                'currentShipCity',
                'currentDestCity',
                'shipCities',
                'destinationCities',
                'landingPage',
                'route',
                'aboutPage',
                'textBlock',
                'servicesPosts'
            ))->render();
    }

    public function cacheClear($url)
    {
        Cache::forget("$url.landingPage");
        Cache::forget("$url.shipCities");
        Cache::forget("$url.shipPoints");
        Cache::forget("$url.selectedShipCity");
        Cache::forget("$url.destinationCities");
        Cache::forget("$url.destinationPoints");
        Cache::forget("$url.destinationCitiesMerged");
        Cache::forget("$url.selectedDestCity");
        Cache::forget("$url.currentShipCity");
        Cache::forget("$url.currentDestCity");
        Cache::forget("$url.aboutPage");
        Cache::forget("$url.textBlock");

        return redirect()->back();
    }

    public function sendMail(Request $request)
    {
        $messages = [
            'g-recaptcha-response.required'  => 'Подтвердите, что Вы не робот.',
        ];

        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => ['required', new GoogleReCaptchaV2()],
            'phone' => ['required'],
        ], $messages);

        if($validator->fails()) {
            return response([
                'data' => $validator->errors()
            ], 400);
        }

        Mail::to("zakaz@123789.ru")->send(new SendLandingMail($request->get('phone')));

        return redirect()->back();
    }

    public function generateAll()
    {
        $routes = Route::where('show_in_price', true)->get();

        foreach($routes as $route) {
            $landingPage = new LandingPage();
            $landingPage->title = "<h1>Грузоперевозки $route->dash_name</h1>";
            $landingPage->route_id = $route->id;
            $landingPage->template = 'default';
            $landingPage->url = '.';

            $daysTitle = TextHelper::daysTitleByCount($route->delivery_time);

            $landingPage->seo_title = "Грузоперевозки $route->dash_name";
            $landingPage->key_words = "Грузоперевозки $route->dash_name, Доставка документов $route->dash_name, Контейнерные перевозки $route->dash_name";
            $landingPage->description = "Балтийская Служба Доставки осуществляет грузоперевозки по маршруту $route->dash_name. От $route->min_cost руб. От $route->delivery_time $daysTitle. Онлайн калькулятор доставки.";

            $landingPage->save();

            $landingPage->url = $landingPage->slug;
            $landingPage->text_1 = LandingPagesHelper::generateText($landingPage, 1);
            $landingPage->text_2 = LandingPagesHelper::generateText($landingPage, 2);
            $landingPage->save();
        }

        return 'ok';
    }
}
