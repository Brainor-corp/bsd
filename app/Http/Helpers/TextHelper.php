<?php

namespace App\Http\Helpers;

class TextHelper {
    public static function daysTitleByCount($count) {
        $titles = ['дня', 'дней', 'дней'];

        $cases = [2, 0, 1, 1, 1, 2];
        $format = $titles[($count % 100 > 4 && $count % 100 < 20) ? 2 : $cases[min($count % 10, 5)]];

        return sprintf($format, $count);
    }
}
