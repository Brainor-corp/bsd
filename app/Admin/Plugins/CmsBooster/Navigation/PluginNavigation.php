<?php

namespace App\Admin\Plugins\CmsBooster\Navigation;

class PluginNavigation
{
    private $pluginNav;

    public function __construct()
    {
        $this->pluginNav =
            [
                'url' => '/'.config('zeusAdmin.admin_url').'/News',
                'icon' => 'fas fa-users',
                'text' => 'Новости'
            ];
    }

    public static function getPluginNav(){
        return (new self)->pluginNav;
    }

}