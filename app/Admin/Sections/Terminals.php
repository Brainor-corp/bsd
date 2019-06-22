<?php

namespace App\Admin\Sections;

use App\City;
use App\Region;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class Terminals extends Section {
    protected $title = 'Терминалы';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Наименование'),
            Column::text('region.name', 'Регион'),
            Column::text('city.name', 'Город'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
                FilterType::select('region_code')
                    ->setIsLike(false)
                    ->setModelForOptions(Region::class)
                    ->setField('code')
                    ->setDisplay("name"),
                FilterType::select('city_id')
                    ->setIsLike(false)
                    ->setModelForOptions(City::class)
                    ->setDisplay("name"),
            ])
            ->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {

        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Наименование')->setRequired(true),
                FormField::input('address', 'Адрес')->setRequired(true),
                FormField::input('phone', 'Телефон')->setRequired(true),
                FormField::bselect('region_code', 'Регион')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Region::class)
                    ->setField('code')
                    ->setDisplay('name'),
                FormField::bselect('city_id', 'Город')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('geo_point', 'Геокоординаты  (x.xxx, y.yyy)')
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