<?php

namespace App\Http\Controllers;

use App\City;
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
        $route = app('App\Http\Controllers\CalculatorController')->getRoute($request, $selectedShipCity,$selectedDestCity);
        $tariff = json_decode(app('App\Http\Controllers\CalculatorController')->getTariff($request, $packages, $selectedShipCity,$selectedDestCity)->content());

        $services = Service::get();

        $currentCityName = null;

        if(Session::has('current_city')) {
            $sessionCity = Session::get('current_city');
            if(isset($sessionCity['name'])) {
                $currentCityName = $sessionCity['name'];
            }
        }

        if(!isset($currentCityName)) {
            $currentCityName = City::where('slug', 'sankt-peterburg')->firstOrFail()->name;
        }

        //news
        $args = [
            'category' => ['novosti'],
            'type' => 'post',
        ];
        $news = CMSHelper::getQueryBuilder($args)
            ->whereHas('terms', function ($promotionsQ) use ($currentCityName) {
                return $promotionsQ->where('title', 'like',  "%$currentCityName%");
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Если для текущего города новостей нет, выведем новости для Питера
        if(!$news->count()) {
            $news = CMSHelper::getQueryBuilder($args)
                ->whereHas('terms', function ($promotionsQ) use ($currentCityName) {
                    return $promotionsQ->where('title', 'like',  "Санкт-Петербург");
                })
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
                'aboutPage'
            ));
    }
}
