<?php
/**
 * class: CmsBooster
 * nameSpace: App\Admin\Plugins\CmsBooster\Providers
 */
namespace App\Admin\Plugins\CmsBooster\Providers;

use App\Admin\Plugins\CmsBooster\Navigation\PluginNavigation;
use Illuminate\Support\ServiceProvider;

class CmsBooster extends ServiceProvider
{
    public $cmsData, $navigation;

    public function __construct(\Illuminate\Contracts\Foundation\Application  $app=null)
    {
        $this->cmsData = [
            'Navigation' => PluginNavigation::getPluginNav()
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