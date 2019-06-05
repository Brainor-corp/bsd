<?php

namespace App\Admin\Sections;

use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Request;


class Oversizes extends Section {
    protected $title = 'Перегруз';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('length', 'Длина'),
            Column::text('width', 'Ширина'),
            Column::text('height', 'Высота'),
            Column::text('volume', 'Обьем'),
            Column::text('weight', 'Вес'),
            Column::text('ratio', 'Пропорция'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
                null,
                null,
                null,
                null,
                null,
                null,
            ])
            ->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {

        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Название')->setRequired(true),
                FormField::input('length', 'Длина')->setType('number')->setRequired(true),
                FormField::input('width', 'Ширина')->setType('number')->setRequired(true),
                FormField::input('height', 'Высота')->setType('number')->setRequired(true),
                FormField::input('volume', 'Обьем')->setType('number')->setRequired(true),
                FormField::input('weight', 'Вес')->setType('number')->setRequired(true),
                FormField::input('ratio', 'Пропорция')->setType('number')->setRequired(true),
            ])
        ]);

        return $form;
    }
}