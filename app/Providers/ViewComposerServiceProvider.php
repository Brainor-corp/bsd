<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 26.04.2019
 * Time: 15:05
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider {

    public function boot() {
        view()->composer("v1.partials.header.header-top", "App\Http\ViewComposers\EventsCounterViewComposer");
        view()->composer("v1.partials.header.header-top", "App\Http\ViewComposers\CurrentCityViewComposer");
        view()->composer("v1.partials.footer.modalSelectionCity", "App\Http\ViewComposers\PopularCitiesViewComposer");
    }

}