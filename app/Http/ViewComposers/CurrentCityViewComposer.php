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
        $sessionCity = (object)\Session::get('current_city');

        $city = null;

        if(isset($sessionCity->id)) {
            $city = City::where([
                ['id', $sessionCity->id],
                ['is_filial', true]
            ])
                ->with('closestTerminal', 'terminals')
                ->first();
        }

        if(!isset($city)) {
            $city = City::where('slug', 'sankt-peterburg')->with('closestTerminal')->first();
        }

        $closestTerminal = $city->closestTerminal;
        $terminals = $city->terminals;

        $view->with(compact('city', 'closestTerminal', 'terminals'));
    }
}
