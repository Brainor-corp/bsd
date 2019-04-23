<?php

namespace App\Admin\Sections;

use App\ForwardThreshold;
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


class PerKmTariffs extends Section {
    protected $title = 'Покилометровые тарифы';
    protected $model = '\App\PerKmTariff';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('real_tariff_zone', 'Тарифная зона'),
            Column::text('real_forward_threshold', 'Предельный порог'),
            Column::text('tariff', 'Тариф'),
        ])
            ->setFilter([
                null,
                FilterType::select('tariff_zone_id')
                    ->setIsLike(false)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($q) {
                        return $q->where('class', 'tariff_zones');
                    })
                    ->setDisplay("name"),
                FilterType::select('forward_threshold_id')
                    ->setIsLike(false)
                    ->setModelForOptions(ForwardThreshold::class)
                    ->setDisplay("name"),
                null,
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
                FormField::select('tariff_zone_id', 'Тарифная зона')
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'tariff_zones');
                    })
                    ->setDisplay('name'),
                FormField::select('forward_threshold_id', 'Предельный порог')
                    ->setRequired(true)
                    ->setModelForOptions(ForwardThreshold::class)
                    ->setDisplay('name'),
                FormField::input('tariff', 'Тариф'),
            ])
        ]);

        return $form;
    }
}