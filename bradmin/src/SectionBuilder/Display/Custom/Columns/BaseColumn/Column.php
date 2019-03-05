<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 01.10.2018
 * Time: 13:07
 */

namespace Bradmin\SectionBuilder\Display\Custom\Columns\BaseColumn;


use Bradmin\SectionBuilder\Display\Custom\Columns\Types\Text;

class Column
{
    public static function text($label, $name)
    {
        return new Text($label, $name);
    }
}