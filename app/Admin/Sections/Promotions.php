<?php

namespace App\Admin\Sections;

use App\Terminal;
use Illuminate\Http\Request;
use Zeus\Admin\Cms\Models\ZeusAdminTerm;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class Promotions extends Section
{
    protected $title = 'Акции';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('title', 'Название'),
            Column::text('amount', 'Скидка (%)'),
            Column::text('start_at', 'Начало акции'),
            Column::text('end_at', 'Окончание акции'),
            Column::text('created_at', 'Дата создания'),
        ])->setPagination(10);

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
                FormField::input('title', 'Название')->setRequired(true),
                FormField::textarea('text', 'Текст'),
                FormField::input('amount', 'Скидка (%)')->setType('number'),
                FormField::bselect('terminals', 'Терминалы')
                    ->setDataAttributes([
                        'multiple', 'data-live-search="true"'
                    ])
                    ->setModelForOptions(Terminal::class)
                    ->setDisplay('name_with_city'),
                FormField::bselect('terms', 'Метки')
                    ->setDataAttributes([
                        'multiple', 'data-live-search="true"'
                    ])
                    ->setModelForOptions(ZeusAdminTerm::class)
                    ->setDisplay('title'),
                FormField::datepicker('start_at', 'Начало акции')
                    ->setLanguage('ru')
                    ->setMinuteStep(10),
                FormField::datepicker('end_at', 'Окончание акции акции')
                    ->setLanguage('ru')
                    ->setMinuteStep(10),
            ])
        ]);

        return $form;
    }


}