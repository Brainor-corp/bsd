<?php

namespace App\Admin\Sections;

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
            Column::text('comprehensive_tariff_zone', 'Тарифная зона'),
            Column::text('comprehensive_threshold_group', 'Группа отправных пунктов'),
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
                FormField::select('is_ship', 'Можно доставить')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::select('is_filial', 'Является филиалом')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::select('doorstep', 'Доставка до двери')
                    ->setOptions([0=>'Нет', 1=>'Да']),
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