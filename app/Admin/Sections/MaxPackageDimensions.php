<?php

namespace App\Admin\Sections;

use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;


class MaxPackageDimensions extends Section
{
    protected $title = 'Макс. габариты места';

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('name', 'Название'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название')
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
                FormField::hidden('name')->setValue('-'),
                FormField::input('length', 'Длина')->setRequired(true),
                FormField::input('width', 'Ширина')->setRequired(true),
                FormField::input('height', 'Высота')->setRequired(true),
            ])
        ]);

        return $form;
    }

    public function afterSave(Request $request, $model = null)
    {
        $model->name = "$model->length-дл. $model->width-шир. $model->height-выс.";
        $model->save();
    }
}
