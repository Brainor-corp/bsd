<?php

namespace App\Admin\Sections;

use App\City;
use App\ForwardThreshold;
use App\Point;
use App\Region;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;


class OutsideForwarding extends Section {
    protected $title = 'Внешние пересылки';
    protected $model = '\App\OutsideForwarding';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
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
                FormField::select('point', 'Пункт')
                    ->setRequired(true)
                    ->setModelForOptions(Point::class)
                    ->setDisplay('name'),
                FormField::select('forward_threshold_id', 'Группа предельных порогов')
                    ->setRequired(true)
                    ->setModelForOptions(ForwardThreshold::class)
                    ->setDisplay('name'),
                FormField::input('tariff', 'Тариф')->setType('number')->setRequired(true),
            ])
        ]);

        return $form;
    }
}