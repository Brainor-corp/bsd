<?php

namespace App\Admin\Sections;

use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class Oversizes extends Section {
    protected $title = 'Группы негабаритов';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('name', 'Наименование'),
            Column::text('length', 'По длине'),
            Column::text('width', 'По ширине'),
            Column::text('height', 'По высоте'),
            Column::text('volume', 'По объёму'),
            Column::text('weight', 'По весу'),
            Column::text('ratio', 'Коэффициент, %'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Наименование'),
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
                FormField::input('name', 'Наименование')
                     ->setHelpBlock("<small class='text-muted'>Наименование группы негабаритов</small>")
                    ->setRequired(true),
                FormField::input('length', 'По длине')
                     ->setHelpBlock("<small class='text-muted'>Негабаритный размер по длине</small>")
                    ->setType('number')->setRequired(true),
                FormField::input('width', 'По ширине')
                     ->setHelpBlock("<small class='text-muted'>Негабаритный размер по ширине</small>")
                    ->setType('number')->setRequired(true),
                FormField::input('height', 'По высоте')
                     ->setHelpBlock("<small class='text-muted'>Негабаритный размер по высоте</small>")
                    ->setType('number')->setRequired(true),
                FormField::input('volume', 'По объёму')
                     ->setHelpBlock("<small class='text-muted'>Негабаритный размер по объёму</small>")
                    ->setType('number')->setRequired(true),
                FormField::input('weight', 'По весу')
                     ->setHelpBlock("<small class='text-muted'>Негабаритный размер по весу</small>")
                    ->setType('number')->setRequired(true),
                FormField::input('ratio', 'Коэффициент, %')
                     ->setHelpBlock("<small class='text-muted'>Коэффициент надбавки за негабаритность в процентах</small>")
                    ->setType('number')->setRequired(true),
            ])
        ]);

        return $form;
    }
}