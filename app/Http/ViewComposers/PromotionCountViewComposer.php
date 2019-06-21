<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 26.04.2019
 * Time: 15:03
 */

namespace App\Http\ViewComposers;

use App\City;
use App\Promotion;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;

class PromotionCountViewComposer
{
    public function compose(View $view) {
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

        $promotionsCount = Promotion::where('end_at', '>', Carbon::now())
            ->whereHas('terms', function ($promotionsQ) use ($currentCityName) {
                return $promotionsQ->where('title', 'like',  "%$currentCityName%");
            })
            ->count();

        $view->with(compact('promotionsCount'));
    }
}