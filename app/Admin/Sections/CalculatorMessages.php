<?php

namespace App\Admin\Sections;

use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;



class CalculatorMessages extends Section
{
    protected $title = 'Информационные блоки';

    protected $checkAccess = true;

    protected $model = "CalculatorMessage";

    public static function onDisplay(){
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('name', 'Название'),
        ])
            ->setPagination(10);

        return $display;
    }

    public static function onCreate()
    {
        return self::onEdit();
    }

    public static function onEdit()
    {
        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Название')
                    ->setHelpBlock("<small>Выводится только в административной панели</small>")
                    ->setRequired(true),
                FormField::Wysiwyg('text', 'Содержимое')->setRequired(true),
            ])
        ]);

        return $form;
    }
}
