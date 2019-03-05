<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 01.10.2018
 * Time: 13:15
 */

namespace Bradmin\SectionBuilder\Display\BaseDisplay;


use Bradmin\SectionBuilder\Display\Custom\DisplayCustom;
use Bradmin\SectionBuilder\Display\Table\DisplayTable;
use Bradmin\SectionBuilder\Display\Tiles\DisplayTiles;

class Display
{
    public static function table($columns = null, $pagination = null)
    {
        return new DisplayTable($columns ?? null, $pagination ?? 15);
    }

    public static function tiles($columns = null, $pagination = null)
    {
        return new DisplayTiles($columns ?? null, $pagination ?? 15);
    }

    public static function custom($columns = null, $pagination = null, $view = null)
    {
        return new DisplayCustom($view ?? null);
    }
}