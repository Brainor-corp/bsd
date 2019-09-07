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

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('code', 'Код'),
            Column::link('name', 'Название'),
            Column::text('fixed_tariff_for_human', 'Фикс. тариф'),
            Column::text('dist_tariff_for_human', 'Тариф по расст.'),
            Column::text('inside_tariff_for_human', 'Внутр. тариф'),
            Column::text('destinationCity.name', 'Город назначения'),
            Column::text('tariffZone.name', 'Тарифная зона'),
            Column::text('thresholdGroup.name', 'Группа отправных пунктов'),
        ])
            ->setFilter([
                null,
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
                FormField::input('code', 'Код')
                    ->setHelpBlock("<small class='text-muted'>Номер записи в базе данных</small>")
                    ->setRequired(true),
                FormField::input('name', 'Наименование')
                    ->setHelpBlock("<small class='text-muted'>Наименование региона</small>")
                    ->setRequired(true),
                FormField::bselect('dest_city_id', 'Город назначения')
                    ->setHelpBlock("<small class='text-muted'>Город назначения груза</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::bselect('threshold_group_id', 'Группа пределов')
                    ->setHelpBlock("<small class='text-muted'>Группа пределов внешней экспедиции</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'threshold_groups');
                    })
                    ->setDisplay('name'),
                FormField::bselect('tariff_zone_id', 'Тарифная зона')
                    ->setHelpBlock("<small class='text-muted'>Тарифная зона внешней экспедиции</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'tariff_zones');
                    })
                    ->setDisplay('name'),
                FormField::bselect('fixed_tariff', 'Фиксированный тариф')
                    ->setHelpBlock("<small class='text-muted'>Применяется фиксированный тариф</small>")
                    ->setOptions([0 => 'Нет', 1 => 'Да'])
                    ->setRequired(true),
                FormField::bselect('dist_tariff', 'Тариф по расстоянию')
                    ->setHelpBlock("<small class='text-muted'>Применяется тариф по расстоянию</small>")
                    ->setOptions([0 => 'Нет', 1 => 'Да'])
                    ->setRequired(true),
                FormField::bselect('inside_tariff', 'Добавить внутренний тариф')
                    ->setHelpBlock("<small class='text-muted'>При рассчете стоимости добавить внутренний тариф</small>")
                    ->setOptions([0 => 'Нет', 1 => 'Да'])
                    ->setRequired(true),
            ])
        ]);

        return $form;
    }


}