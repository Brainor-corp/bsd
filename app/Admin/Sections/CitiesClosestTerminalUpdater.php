<?php

namespace App\Admin\Sections;

use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\Custom\DisplayCustom;

class CitiesClosestTerminalUpdater extends Section
{
    protected $title = 'Обновление ближайших терминалов городов';

    public static function onDisplay(){
        $display = new DisplayCustom();
        $display->setView(view('admin.cities-closest-terminal-updater.index'));

        return $display;
    }
}