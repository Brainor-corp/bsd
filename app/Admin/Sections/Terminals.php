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

//use Illuminate\Support\Facades\Request;


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
                FormField::select('region_code', 'Регион')
                    ->setRequired(true)
                    ->setModelForOptions(Region::class)
                    ->setField('code')
                    ->setDisplay('name'),
                FormField::select('city_id', 'Город')
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('street', 'Улица')->setRequired(true),
                FormField::input('house', 'Дом')->setRequired(true),
                FormField::input('geo_point', 'Гео. точка (x.xxx, y.yyy)')
                ->setPattern("(\d+\.\d+|\d+),\s(\d+\.\d+|\d+)"),
            ])
        ]);

        return $form;
    }

    public function afterSave(Request $request, $model = null)
    {
        if(empty($request->get('geo_point'))) {
            $model->geo_point = "0, 0";
            $model->save();
        }
    }
}