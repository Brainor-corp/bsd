<?php

namespace App\Http\Controllers;

use App\City;
use App\Promotion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class PromotionsController extends Controller
{
    public function showList() {
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

        $promotions = Promotion::where('end_at', '>', Carbon::now())
            ->whereHas('terms', function ($promotionsQ) use ($currentCityName) {
                return $promotionsQ->where('title', 'like',  "%$currentCityName%");
            })
            ->get();

        return view('v1.pages.promotions.list.list')->with(compact('promotions'));
    }

    public function showSinglePromotion($slug){
        $promotion = Promotion::where([['end_at', '>', Carbon::now()], ['slug', $slug]])->firstOrFail();

        return View::make('v1.pages.promotions.single.single-promotion')->with(compact('promotion'));
    }
}
