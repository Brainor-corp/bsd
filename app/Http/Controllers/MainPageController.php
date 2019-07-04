<?php

namespace App\Http\Controllers;

use App\City;
use App\CmsBoosterPost;
use App\Http\Helpers\CalculatorHelper;
use App\Route;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Zeus\Admin\Cms\Helpers\CMSHelper;

class MainPageController extends Controller
{
    public function index(Request $request) {
        if(isset($request->packages)){
            $packages = $request->packages;
        }else{
            $packages=[
                1=>[
                    'length' => '0.1',
                    'width' => '0.1',
                    'height' => '0.1',
                    'weight' => '1',
                    'volume' => '0.01',
                    'quantity' => '1'
                ]
            ];
        }

        $shipCities = City::where('is_ship', true)->get();
        $selectedShipCity = null;
        $selectedDestCity = null;
        if(isset($request->ship_city)){
            $selectedShipCity = $request->ship_city;
        }else{
            $selectedShipCity = 53;
        }
        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', $selectedShipCity))
            ->orderBy('name')
            ->get();

        if(isset($request->ship_city) && isset($request->dest_city)){
            $selectedDestCity = $request->dest_city;
        }else{
            $selectedDestCity = 78;
        }

        $shipCity = City::where('id', $selectedShipCity)->firstOrFail();
        $destCity = City::where('id', $selectedDestCity)->firstOrFail();

        $routeData = CalculatorHelper::getRouteData($shipCity, $destCity, $packages);
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
        $news = CmsBoosterPost::when(
                isset($currentCity->closest_terminal_id),
                function ($newsQ) use ($currentCity) {
                    return $newsQ->whereHas('terminals', function ($terminalsQ) use ($currentCity) {
                        $terminalsQ->where('id', $currentCity->closest_terminal_id);
                    });
                }
            )
            ->where([
                ['type', 'news'],
                ['status', 'published'],
            ])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Если для текущего города новостей нет, и текущий город -- не Питер, выведем новости для Питера
        if(!$news->count() && $currentCity->slug !== 'sankt-peterburg') {
            $news = CmsBoosterPost::whereHas('terminals', function ($terminalsQ) use ($currentCity) {
                    $terminalsQ->whereHas('city', function ($cityQ) {
                        return $cityQ->where('slug', 'sankt-peterburg');
                    });
                })
                ->where([
                    ['type', 'news'],
                    ['status', 'published'],
                ])
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        }

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
                'selectedShipCity',
                'selectedDestCity',
                'news',
                'servicesPostsChunk',
                'aboutPage',
                'textBlock'
            ));
    }
}
