<?php
/**
 * class: Cms
 * nameSpace: Bradmin\Cms\Providers
 */
namespace Bradmin\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Bradmin\Cms\Navigation\PluginNavigation;

use Bradmin\Cms\Exceptions\BradminExceptionsHandler;
use Illuminate\Contracts\Debug\ExceptionHandler;

class Cms extends ServiceProvider
{
    public $navigation;
    public $cmsData;

    public function __construct(\Illuminate\Contracts\Foundation\Application  $app=null)
    {
        $this->navigation = PluginNavigation::getPluginNav();
        $this->cmsData = [
            '1'=>'5555',
            '2'=>'6666'
        ];
        parent::__construct($app);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {

        // load config
        $this->mergeConfigFrom(__DIR__.'/../../../config/bradmin.php', 'bradmin');
        // load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        // load view files
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cms');
        // publish files
        $this->publishes([__DIR__.'/../resources/views' => resource_path('views/bradmin/cms')]);
        $this->loadMigrationsFrom(__DIR__.'/../Migrations');

        $this->app->bind(
            ExceptionHandler::class,
            BradminExceptionsHandler::class
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}