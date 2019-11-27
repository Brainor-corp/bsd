<?php

namespace App\Admin\Sections;

use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

class Permissions extends Section
{
    protected $title = 'Привелегии';

    protected $checkAccess = true;

    public static function onDisplay(){
        $display = Display::table([
            Column::link('id', '#'),
            Column::text('name', 'Название'),
        ]);

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
                FormField::input('name', 'Название')->setRequired(true),
                FormField::input('slug', 'Слаг (необязательно)'),
            ])
        ]);

        return $form;
    }


}