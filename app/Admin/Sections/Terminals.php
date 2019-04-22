<?php

namespace App\Admin\Sections;

use App\City;
use App\Oversize;
use App\Region;
use App\Route;
use App\RouteTariff;
use App\Threshold;
use App\Type;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Display\Table\DisplayTable;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
//use Illuminate\Support\Facades\Request;
use Bradmin\SectionBuilder\Meta\Meta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;


class Terminals extends Section {
    protected $title = 'Терминалы';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('short_name', 'Кор. название'),
            Column::text('real_region', 'Регион'),
            Column::text('real_city', 'Город'),
            Column::text('street', 'Улица'),
            Column::text('house', 'Дом'),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {

        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Название')->setRequired(true),
                FormField::input('short_name', 'Кор. название')->setRequired(true),
                FormField::input('region', 'Код региона'),
//                FormField::select('region_code', 'Регион')
//                    ->setRequired(true)
//                    ->setModelForOptions(Region::class)
//                    ->setDisplay('name'),
                FormField::select('city_id', 'Город')
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('street', 'Улица')->setRequired(true),
                FormField::input('house', 'Дом')->setRequired(true),
                FormField::input('geo_point', 'Гео. точка'),
            ])
        ]);

        return $form;
    }
}