<?php

namespace App\Admin\Sections;

use App\City;
use App\Type;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;

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
            Column::text('comprehensive_destination_city', 'Город назначения'),
            Column::text('comprehensive_tariff_zone', 'Тарифная зона'),
            Column::text('comprehensive_threshold_group', 'Группа отправных пунктов'),
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
                FormField::select('dest_city_id', 'Город назначения')
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::select('tariff_zone_id', 'Тарифная зона')
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'tariff_zones');
                    })
                    ->setDisplay('name'),
                FormField::select('threshold_group_id', 'Группа отправных пунктов')
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