<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 01.10.2018
 * Time: 13:07
 */

namespace Bradmin\SectionBuilder\Display\Tiles\Columns\BaseElement;


use Bradmin\SectionBuilder\Display\Tiles\Columns\Types\Text;

class Element
{
    public static function text($label, $name)
    {
        return new Text($label, $name);
    }
}