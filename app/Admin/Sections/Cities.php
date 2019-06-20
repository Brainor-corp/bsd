<?php

namespace App\Admin\Sections;

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


class Cities extends Section
{
    protected $title = 'Города';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('comprehensive_is_ship', 'Можно доставить'),
            Column::text('comprehensive_is_filial', 'Является филиалом'),
            Column::text('comprehensive_doorstep', 'Доставка до двери'),
            Column::text('tariffZone.name', 'Тарифная зона'),
            Column::text('thresholdGroup.name', 'Группа отправных пунктов'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
                null,
                null,
                null,
                null,
                null,
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
                FormField::input('message', 'Сообщение')->setType('number'),
                FormField::bselect('is_ship', 'Можно доставить')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::bselect('is_filial', 'Является филиалом')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::bselect('doorstep', 'Доставка до двери')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::bselect('is_popular', 'Показывать в популярных городах')
                    ->setOptions([0=>'Нет', 1=>'Да']),
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