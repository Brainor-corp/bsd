<?php

namespace App\Admin\Sections;

use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class Comments extends Section {
    protected $title = 'Отзывы';

    protected $checkAccess = true;

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::link('id', '#'),
            Column::text('user_id', 'id пользователя'),
            Column::text('email', 'Email'),
            Column::text('fio', 'ФИО'),
            Column::text('rating', 'Рейтинг'),
            Column::text('comprehensible_visible', 'Отображать'),
            Column::text('comprehensible_moderate', 'На модерации'),
        ])->setPagination(10);

        return $display;
    }

    public function isCreatable() {
        return false;
    }

    public static function onEdit($id) {

        $form = Form::panel([
            FormColumn::column([
                FormField::input('user_id', 'id пользователя')->setReadonly(true)->setRequired(true),
                FormField::input('email', 'email пользователя')->setReadonly(true)->setRequired(true),
                FormField::input('fio', 'ФИО пользователя')->setReadonly(true)->setRequired(true),
                FormField::input('ip', 'ip пользователя')->setReadonly(true)->setRequired(true),
                FormField::textarea('text', 'Текст отзыва')->setReadonly(true),
                FormField::input('rating', 'Рейтинг отзыва')->setReadonly(true)->setRequired(true),
                FormField::bselect('visible', 'Отображать отзыв')
                    ->setRequired(true)
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::bselect('moderate', 'На модерации')
                    ->setRequired(true)
                    ->setOptions([0=>'Нет', 1=>'Да']),
            ])
        ]);

        return $form;
    }
}