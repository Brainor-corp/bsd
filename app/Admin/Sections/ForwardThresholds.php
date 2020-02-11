<?php

namespace App\Admin\Sections;

use App\Type;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class ForwardThresholds extends Section {
    protected $title = 'Пределы габаритов';
    protected $model = '\App\ForwardThreshold';

    protected $checkAccess = true;

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::link('id', '#'),
            Column::text('thresholdGroup.name', 'Группа отправных пунктов'),
            Column::text('name', 'Название'),
            Column::text('weight', 'Вес'),
            Column::text('volume', 'Обьем'),
            Column::text('units', 'Единиц'),
            Column::text('length', 'Длина'),
            Column::text('width', 'Ширина'),
            Column::text('height', 'Высота'),
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
                FormField::hidden('name')->setValue('-'),
                FormField::hidden('name_params')->setValue('-'),
                FormField::hidden('name_dimensions')->setValue('-'),
                FormField::input('weight', 'Вес')->setType('number')
                    ->setDataAttributes([
                        'step=any',
                        'min=0'
                    ])->setRequired(true),
                FormField::input('volume', 'Обьем')->setType('number')
                    ->setDataAttributes([
                        'step=any',
                        'min=0'
                    ])->setRequired(true),
                FormField::input('units', 'Единиц')->setType('number')
                    ->setDataAttributes([
                        'step=any',
                        'min=0'
                    ])->setRequired(true),
                FormField::input('length', 'Длина')->setType('number')
                    ->setDataAttributes([
                        'step=any',
                        'min=0'
                    ]),
                FormField::input('width', 'Ширина')->setType('number')
                    ->setDataAttributes([
                        'step=any',
                        'min=0'
                    ]),
                FormField::input('height', 'Высота')->setType('number')
                    ->setDataAttributes([
                        'step=any',
                        'min=0'
                    ]),
                FormField::bselect('threshold_group_id', 'Группа пределов')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
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

    public function afterSave(Request $request, $model = null)
    {
        $model->name_params = "До $model->weight кг, до $model->volume м3, до $model->units шт.";
        $model->name_dimensions = "$model->length дл., $model->width шир., $model->height выс.";
        $model->name = "$model->name_params, $model->name_dimensions";
        $model->save();
    }
}
