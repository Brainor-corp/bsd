<?php

namespace App\Admin\Sections;

use App\Oversize;
use App\Threshold;
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


class Companies extends Section
{
    protected $title = 'Компании';

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('name', 'Название'),

        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
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
                FormField::input('name', 'Наименование')
                    ->setRequired(true),
                FormField::related('oversizes', 'Группы негабаритов', Oversize::class, [
                    FormField::input('name', 'Наименование')
                        ->setRequired(true),
                    FormField::input('length', 'По длине')
                        ->setDataAttributes([
                            'step=any',
                            'min=0'
                        ])
                        ->setType('number')
                        ->setRequired(true),
                    FormField::input('width', 'По ширине')
                        ->setDataAttributes([
                            'step=any',
                            'min=0'
                        ])
                        ->setType('number')
                        ->setRequired(true),
                    FormField::input('height', 'По высоте')
                        ->setDataAttributes([
                            'step=any',
                            'min=0'
                        ])
                        ->setType('number')
                        ->setRequired(true),
                    FormField::input('volume', 'По объёму')
                        ->setDataAttributes([
                            'step=any',
                            'min=0'
                        ])
                        ->setType('number')
                        ->setRequired(true),
                    FormField::input('weight', 'По весу')
                        ->setDataAttributes([
                            'step=any',
                            'min=0'
                        ])
                        ->setType('number')
                        ->setRequired(true),
                    FormField::input('ratio', 'Коэффициент, %')
                        ->setDataAttributes([
                            'step=any',
                            'min=0'
                        ])
                        ->setType('number')
                        ->setRequired(true),
                ]),
                FormField::related('thresholds', 'Маршрутные пределы', Threshold::class, [
                    FormField::bselect('rate_id', 'Показатель')
                        ->setDataAttributes([
                            'data-live-search="true"'
                        ])
                        ->setRequired(true)
                        ->setModelForOptions(Type::class)
                        ->setQueryFunctionForModel(function ($query){
                            return $query->where('class', 'rates');
                        })
                        ->setDisplay('name'),
                    FormField::input('value', 'Величина')
                        ->setRequired(true)
                        ->setType('number'),
                ])
            ])
        ]);

        return $form;
    }

    public function beforeDelete(Request $request, $id = null)
    {
        Oversize::where('company_id', $id)->delete();
        Threshold::where('company_id', $id)->delete();
    }
}