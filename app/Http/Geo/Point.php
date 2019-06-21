<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 21.06.2019
 * Time: 14:20
 */

namespace App\Http\Geo;


class Point
{
    public $x, $y;

    function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    function distanceTo(Point $point) {
        $distanceX = $this->x - $point->x;
        $distanceY = $this->y - $point->y;
        $distance = sqrt($distanceX * $distanceX + $distanceY * $distanceY);
        return $distance;
    }

    function __toString() {
        return 'x: ' . $this->x . ', y: ' . $this->y;
    }
}