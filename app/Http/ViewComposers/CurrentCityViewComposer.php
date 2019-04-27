<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 26.04.2019
 * Time: 15:03
 */

namespace App\Http\ViewComposers;

use App\City;
use Illuminate\Contracts\View\View;

class CurrentCityViewComposer
{
    public function compose(View $view) {
        $city = null;

        if(isset($_COOKIE['current_city']) && is_numeric($_COOKIE['current_city'])) {
            $city = City::where('id', $_COOKIE['current_city'])->first();
        }

        if(!$city) {
            $city = City::where('slug', 'sankt-peterburg')->first();
        }

        $view->with(compact('city'));
    }
}