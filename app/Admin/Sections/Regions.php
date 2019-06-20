<?php

namespace App\Admin\Sections;

use App\City;
use App\Type;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class Regions extends Section
{
    protected $title = 'Регионы';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('code', 'Код'),
            Column::text('name', 'Название'),
            Column::text('fixed_tariff', 'Фикс. тариф'),
            Column::text('dist_tariff', 'Дист. тариф'),
            Column::text('inside_tariff', 'Внут. тариф'),
            Column::text('destinationCity.name', 'Город назначения'),
            Column::text('tariffZone.name', 'Тарифная зона'),
            Column::text('thresholdGroup.name', 'Группа отправных пунктов'),
        ])
            ->setFilter([
                FilterType::text('code', 'Код'),
                FilterType::text('name', 'Название'),
                null,
                null,
                null,
                FilterType::select('dest_city_id')
                    ->setIsLike(false)
                    ->setModelForOptions(City::class)
                    ->setDisplay("name"),
                FilterType::select('tariff_zone_id')
                    ->setIsLike(false)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($q) {
                        return $q->where('class', 'tariff_zones');
                    })
                    ->setDisplay("name"),
                FilterType::select('threshold_group_id')
                    ->setIsLike(false)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($q) {
                        return $q->where('class', 'threshold_groups');
                    })
                    ->setDisplay("name"),
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
                FormField::input('code', 'Код')->setRequired(true),
                FormField::input('fixed_tariff', 'Фикс. тариф')->setType('number')->setRequired(true),
                FormField::input('dist_tariff', 'Дист. тариф')->setType('number')->setRequired(true),
                FormField::input('inside_tariff', 'Внут. тариф')->setType('number')->setRequired(true),
                FormField::bselect('dest_city_id', 'Город назначения')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::bselect('tariff_zone_id', 'Тарифная зона')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'tariff_zones');
                    })
                    ->setDisplay('name'),
                FormField::bselect('threshold_group_id', 'Группа отправных пунктов')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'threshold_groups');
                    })
                    ->setDisplay('name'),
            ])
        ]);

        return $form;
    }


}