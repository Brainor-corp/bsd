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
                FormField::input('name_params', 'Заголовок (объём/вес/кол-во)')
                    ->setHelpBlock('<small class="text-muted">Отображается на странице прайс-листа в строке "габариты"</small>')
                    ->setRequired(true),
                FormField::input('name_dimensions', 'Заголовок (длина/ширина/высота)')
                    ->setHelpBlock('<small class="text-muted">Отображается на странице прайс-листа в строке "максимальные габариты 1 места"</small>'),
                FormField::input('weight', 'Вес')->setType('number')->setRequired(true),
                FormField::input('volume', 'Обьем')->setType('number')->setRequired(true),
                FormField::input('units', 'Единиц')->setType('number')->setRequired(true),
                FormField::input('length', 'Длина')->setType('number'),
                FormField::input('width', 'Ширина')->setType('number'),
                FormField::input('height', 'Высота')->setType('number'),
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
        $model->name = "$model->name_params, $model->name_dimensions";
        $model->save();
    }
}
