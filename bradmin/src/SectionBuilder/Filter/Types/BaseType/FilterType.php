<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 04.12.2018
 * Time: 12:29
 */

namespace Bradmin\SectionBuilder\Filter\Types\BaseType;

use Bradmin\SectionBuilder\Filter\Types\Text;
use Bradmin\SectionBuilder\Filter\Types\Select;

class FilterType
{
    public static function text($name = null, $placeholder = null)
    {
        return new Text($name, $placeholder);
    }

    public static function select($name = null)
    {
        return new Select($name);
    }
}