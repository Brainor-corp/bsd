<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Date\Date;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Date::setLocale('ru');
        Schema::defaultStringLength(191);

        // Убираем лишние ЦМС пункты из админки
        $removeNavs = ["Записи", "Комментарии", "Метки"];
        $modifiedPluginData = $this->app['PluginsData'];

        $modifiedPluginData['PluginsNavigation'][0]['nodes'] = array_map(function ($item) use ($removeNavs) {
            return in_array($item['text'], $removeNavs) ? null : $item;
        }, $modifiedPluginData['PluginsNavigation'][0]['nodes']);

        $this->app['PluginsData'] = $modifiedPluginData;
        //

        \Illuminate\Support\Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value) || is_object($value)) {
                    return collect($value)->recursive();
                }

                return $value;
            });
        });
    }
}
