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
        $slugs = [
            'sankt-peterburg',
            'moskva',
            'rostov-na-donu',
            'krasnodar',
            'vladivostok',
            'khabarovsk',
            'chita',
            'nizhniy-novgorod',
            'kazan',
        ];

        $popularCities = City::whereIn('slug', $slugs)->select('name', 'slug', 'id')->get();
        $popularCities = $popularCities->sortBy(function ($city) use ($slugs) {
            return array_search($city->slug, $slugs);
        });

        $view->with(compact('popularCities'));
    }
}
