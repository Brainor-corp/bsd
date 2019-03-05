<?php

namespace App\Providers;


use Bradmin\Cms\Helpers\CMSHelper;
use Illuminate\Support\ServiceProvider;

class CMSHelpersProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('CMS', CMSHelper::class);
    }
}
