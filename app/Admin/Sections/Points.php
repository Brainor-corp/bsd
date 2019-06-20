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


class Points extends Section
{
    protected $title = 'Пункты';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('region.name', 'Регион'),
            Column::text('city.name', 'Город'),
            Column::text('distance', 'Расстояние'),
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
                null
            ])
            ->setPagination(10);

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
                FormField::bselect('region_code', 'Регион')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Region::class)
                    ->setField('code')
                    ->setDisplay('name'),
                FormField::bselect('city_id', 'Город')
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
            ])
        ]);

        return $form;
    }


}