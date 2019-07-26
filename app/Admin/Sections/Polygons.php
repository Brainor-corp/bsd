<?php

namespace App\Admin\Sections;

use App\City;
use App\Terminal;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;


class Polygons extends Section
{
    protected $title = 'Полигоны';

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('name', 'Название'),
            Column::text('city.name', 'Город'),
            Column::text('price', 'Тариф'),
            Column::text('priority', 'Приоритет'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
                FilterType::bselect('city_id')
                    ->setDataAttributes([
                        'data-live_search="true"'
                    ])
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                null
            ])
            ->setPagination(10);

        return $display;
    }

    public static function onCreate()
    {
        return self::onEdit(null);
    }

    public static function onEdit($id)
    {
        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Наименование')
                    ->setRequired(true),
                FormField::input('price', 'Цена')
                    ->setType('number')
                    ->setDataAttributes([
                        'step="any"', 'min=0'
                    ])
                    ->setRequired(true),
                FormField::input('priority', 'Приоритет')
                    ->setType('number')
                    ->setHelpBlock("<small class='text-muted'>Если адрес попадает одновременно в несколько полигонов, для расчета будет использована цена полигона с наименьщим приоритетом</small>")
                    ->setRequired(true),
                FormField::textarea('coordinates', 'Координаты')
                    ->setHelpBlock("<small class='text-muted'>Координаты области в виде [x1, y1], [x2, y2] ...</small>")
                    ->setRequired(1),
                FormField::bselect('city_id', 'Город')
                    ->setHelpBlock("<small class='text-muted'>Город, внутри которого находится полигон</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
            ])
        ]);

        return $form;
    }

    public function beforeDelete(Request $request, $id = null)
    {
        \App\InsideForwarding::where('city_id', $id)->delete();
        Terminal::where('city_id', $id)->delete();
    }
}