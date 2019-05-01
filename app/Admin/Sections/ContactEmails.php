<?php

namespace App\Admin\Sections;

use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;


class ContactEmails extends Section
{
    protected $title = 'Имейлы для связи';
    protected $model = '\App\ContactEmail';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('email', 'Email'),
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
                FormField::select('active', 'Активный')
                ->setOptions([0 => 'Нет', 1 => 'Да']),
            ])
        ]);

        return $form;
    }

}