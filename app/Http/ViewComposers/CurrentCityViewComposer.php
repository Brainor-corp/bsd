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
use Illuminate\Support\Facades\Request;


class CurrentCityViewComposer
{
    public function compose(View $view) {
        $city = (object)\Session::get('current_city');

        $view->with(compact('city'));
    }
}