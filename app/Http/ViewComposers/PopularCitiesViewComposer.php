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

class PopularCitiesViewComposer
{
    public function compose(View $view) {
        $popularCities = City::where('is_popular', true)
            ->select('name', 'id')
            ->get();

        $view->with(compact('popularCities'));
    }
}