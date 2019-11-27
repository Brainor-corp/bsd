<?php

namespace App\Admin\Sections;

use App\Permission;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

class Roles extends Section
{
    protected $title = 'Роли';

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
                FormField::bselect('permissions', 'Привелегии')
                    ->setDataAttributes([
                        'multiple'
                    ])
                    ->setModelForOptions(Permission::class)
                    ->setDisplay('name'),
            ])
        ]);

        return $form;
    }


}