<?php

namespace App\Admin\Sections;

use App\City;
use App\Region;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;


class Points extends Section
{
    protected $title = 'Пункты';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('real_region', 'Регион'),
            Column::text('real_city', 'Город'),
            Column::text('distance', 'Расстояние'),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate()
    {
        return self::onEdit(null);
    }

    public static function onEdit($id)
    {
        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Название')->setRequired(true),
//                FormField::input('region_code', 'Код региона')->setRequired(true),
                FormField::select('region_code', 'Регион')
                    ->setRequired(true)
                    ->setModelForOptions(Region::class)
                    ->setField('code')
                    ->setDisplay('name'),
                FormField::select('city_id', 'Город')
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
            ])
        ]);

        return $form;
    }


}