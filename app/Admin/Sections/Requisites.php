<?php

namespace App\Admin\Sections;

use App\City;
use App\RequisitePart;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;


class Requisites extends Section
{
    protected $title = 'Реквизиты';

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('name', 'Наименование'),
            Column::text('city.name', 'Город'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Наименование'),
                FilterType::bselect('city_id', 'Город')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setModelForOptions(City::class)
                    ->setDisplay('name')

            ])
            ->setPagination(10);

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
                FormField::input('name', 'Наименование')
                    ->setHelpBlock("<small class='text-muted'>Наименование. Прим.: Банковские реквизиты.</small>")
                    ->setRequired(1),
                FormField::bselect('city_id', 'Город')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setHelpBlock("<small class='text-muted'>Город, к которому привязаны реквизиты</small>")
                    ->setModelForOptions(City::class)
                    ->setDisplay('name')
                    ->setRequired(1),
                FormField::input('sort', 'Порядок вывода')
                    ->setType('number')
                    ->setHelpBlock("<small class='text-muted'>Определяет порядок вывода, если выводится несколько реквизитов</small>")
                    ->setValue(1)
                    ->setRequired(1),
                FormField::related('requisiteParts', 'Реквизиты', RequisitePart::class, [
                    FormField::input('name', 'Наименование')->setRequired(1),
                    FormField::input('value', 'Значение')->setRequired(1),
                ]),
            ])
        ]);

        return $form;
    }

    public function beforeDelete(Request $request, $id = null)
    {
        RequisitePart::where('requisite_id', $id)->delete();
    }
}