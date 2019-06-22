<?php

namespace App\Admin\Sections;

use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;


class ContactEmails extends Section
{
    protected $title = 'Имейлы для связи';
    protected $model = '\App\ContactEmail';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::link('email', 'Email'),
            Column::text('description', 'Описание'),
            Column::text('comprehensible_active', 'Активный'),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate()
    {
        return self::onEdit();
    }

    public static function onEdit(){
        $form = Form::panel([
            FormColumn::column([
                FormField::input('email', 'Email')->setType('email')->setRequired(true),
                FormField::textarea('description', 'Описание'),
                FormField::bselect('active', 'Активный')
                ->setOptions([0 => 'Нет', 1 => 'Да']),
            ])
        ]);

        return $form;
    }

}