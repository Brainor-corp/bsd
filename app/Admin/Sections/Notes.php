<?php

namespace App\Admin\Sections;

use App\Type;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class Notes extends Section {
    protected $title = 'Заметки';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::link('id', '#'),
            Column::text('real_type', 'Тип заметки'),
            Column::text('text', 'Текст заметки'),
            Column::text('option', 'Наименование'),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {

        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Наименование')->setRequired(true),
                FormField::input('value', 'Данные')->setRequired(true),
                FormField::input('description', 'Описание'),
                FormField::input('group', 'Группа'),
                FormField::bselect('type_id', 'Тип контакта')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'contacts');
                    })
                    ->setDisplay('name'),
            ])
        ]);

        return $form;
    }
}