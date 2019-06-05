<?php

namespace App\Admin\Sections;

use App\City;
use App\Oversize;
use App\Region;
use App\Route;
use App\RouteTariff;
use App\Threshold;
use App\Type;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Display\Table\DisplayTable;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
//use Illuminate\Support\Facades\Request;
use Zeus\Admin\SectionBuilder\Meta\Meta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;


class Supports extends Section {
    protected $title = 'Обращения';

    public static function onDisplay() {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('fio', 'ФИО'),
            Column::text('company_name', 'Название компании'),
            Column::text('subject.name', 'Тип обращения'),
            Column::text('created_at', 'Дата создания'),
            Column::text('updated_at', 'Дата обновления'),
        ])
            ->setFilter([
                null,
                null,
                null,
                FilterType::select('subject_id')->setModelForOptions(Type::class)->setQueryFunctionForModel(function ($type){
                    return $type->where('class', 'support_type');
                })->setDisplay('name'),
                null,
                null,
            ])
            ->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit();
    }

    public static function onEdit() {

        $form = Form::panel([
            FormColumn::column([
                FormField::input('fio', 'ФИО'),
                FormField::input('company_name', 'Название компании'),
                FormField::input('phone', 'Телефон')->setRequired(true),
                FormField::textarea('text', 'Текст'),
                FormField::select('subject_id', 'Тип обращения')
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'support_type');
                    })
                    ->setDisplay('name'),
            ])
        ]);

        return $form;
    }
}