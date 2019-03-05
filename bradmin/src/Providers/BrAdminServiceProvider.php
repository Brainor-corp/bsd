<?php

namespace Bradmin\Providers;

use Illuminate\Support\ServiceProvider;
use Bradmin\Plugins\PluginManager;

class BrAdminServiceProvider extends ServiceProvider
{
    public $allPluginsNavigation = [];
    public $allPluginsCmsData = [];

    public function __construct(\Illuminate\Contracts\Foundation\Application  $app=null)
    {
       $this->allPluginsNavigation;
       $this->allPluginsCmsData;
        parent::__construct($app);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {
        require(__DIR__ . '/../../vendor/autoload.php');
        // load config
        $this->mergeConfigFrom(__DIR__.'/../../config/bradmin.php', 'bradmin');

        // load routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        // load view files
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'bradmin');

        // publish files
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/bradmin'),
            __DIR__.'/../../public/packages/bradmin/js/ckeditor' => public_path('packages/bradmin/js'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // load config
        $this->mergeConfigFrom(__DIR__.'/../../config/bradmin.php', 'bradmin');

        /*
        * Register the service provider for the dependency.
        */

//        $this->app->register('Intervention\Image\ImageServiceProvider');

        /*
        * Create aliases for the dependency.
        */

//        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
//        $loader->alias('Image', 'Intervention\Image\Facades\Image');

        $this->app->singleton('PluginManager', function($app)
        {
            return new PluginManager();
        });

        $pluginManager = $this->app->make('PluginManager');

        // Register other plugin Service Providers in a loop here
        foreach ($pluginManager->getInstalledPlugins() as $pluginProviders)
        {
            foreach ($pluginProviders['providers'] as $pluginProvider)
            {
                $this->app->register($pluginProvider['nameSpace'].'\\'.$pluginProvider['class']);

                $pluginData = $this->app->{$pluginProvider['nameSpace'].'\\'.$pluginProvider['class']};

                if(isset($pluginData->navigation)){
                    $this->allPluginsNavigation = array_merge($this->allPluginsNavigation,$pluginData->navigation);
                }
                if(isset($pluginData->cmsData)){
                    $this->allPluginsCmsData = array_merge($this->allPluginsCmsData,$pluginData->cmsData);
                }
            }
        }
        $this->app->bind('PluginsData', function(){
            return [
                'PluginsNavigation'=>$this->allPluginsNavigation,
                'CmsData'=>$this->allPluginsCmsData,
            ];
        });

        //CMS
        $this->app->register('Bradmin\Cms\Providers\Cms');

        $pluginData = $this->app->{'Bradmin\Cms\Providers\Cms'};

        if(isset($pluginData->navigation)){
            $this->allPluginsNavigation = array_merge($this->allPluginsNavigation,$pluginData->navigation);
        }
        if(isset($pluginData->cmsData)){
            $this->allPluginsCmsData = array_merge($this->allPluginsCmsData,$pluginData->cmsData);
        }
        //END CMS
    }
}