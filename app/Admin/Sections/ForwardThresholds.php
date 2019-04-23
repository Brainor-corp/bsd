<?php

namespace App\Admin\Sections;

use App\Type;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Request;


class ForwardThresholds extends Section {
    protected $title = 'Предельные пороги';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('real_threshold', 'Группа отправных пунктов'),
            Column::text('name', 'Название'),
            Column::text('weight', 'Вес'),
            Column::text('volume', 'Обьем'),
            Column::text('units', 'Едениц'),
        ])
            ->setFilter([
                null,
                FilterType::select('threshold_group_id')
                    ->setIsLike(false)
                    ->setModelForOptions(Type::class)
                    ->setDisplay("name")
                    ->setQueryFunctionForModel(function ($q) {
                        $q->where('class', 'threshold_groups');
                    }),
                FilterType::text('name', 'Название'),
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
                FormField::input('weight', 'Вес')->setType('number')->setRequired(true),
                FormField::input('volume', 'Обьем')->setType('number')->setRequired(true),
                FormField::input('units', 'Едениц')->setType('number')->setRequired(true),
                FormField::select('threshold_group_id', 'Группа отправных пунктов')
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'threshold_groups');
                    })
                    ->setDisplay('name'),
            ])
        ]);

        return $form;
    }
}