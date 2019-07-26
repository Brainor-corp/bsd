<?php

namespace App\Admin\Sections;

use App\ForwardThreshold;
use App\Point;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;


class OutsideForwarding extends Section {
    protected $title = 'Внешние пересылки';
    protected $model = '\App\OutsideForwarding';

    protected $checkAccess = true;

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::link('id', '#'),
            Column::text('point_relation.name', 'Пункт'),
            Column::text('forwardThreshold.name', 'Группа предельных порогов'),
            Column::text('tariff', 'Тариф'),
        ])
            ->setFilter([
                null,
                FilterType::select('point')
                    ->setIsLike(false)
                    ->setModelForOptions(Point::class)
                    ->setDisplay("name"),
                FilterType::select('forward_threshold_id')
                    ->setIsLike(false)
                    ->setModelForOptions(ForwardThreshold::class)
                    ->setDisplay("name"),
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
                FormField::bselect('point', 'Пункт')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Point::class)
                    ->setDisplay('name'),
                FormField::bselect('forward_threshold_id', 'Группа предельных порогов')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(ForwardThreshold::class)
                    ->setDisplay('name'),
                FormField::input('tariff', 'Тариф')
                    ->setDataAttributes([
                        'step=any',
                        'min=0'
                    ])
                    ->setType('number')
                    ->setRequired(true),
            ])
        ]);

        return $form;
    }
}