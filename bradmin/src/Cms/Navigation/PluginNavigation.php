<?php

namespace Bradmin\Cms\Navigation;

class PluginNavigation
{

    private $pluginNav;

    public function __construct()
    {
        $this->pluginNav = [
            []
        ];
    }

    public static function getPluginNav(){
        return (new self())->pluginNav;
    }

}