<?php

namespace App\Admin\Sections;

use App\City;
use App\Region;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;


class Points extends Section
{
    protected $title = 'Особые населенные пункты';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('region.name', 'Регион'),
            Column::text('city.name', 'Город'),
            Column::text('distance', 'Расстояние'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
                FilterType::select('region_code')
                    ->setIsLike(false)
                    ->setModelForOptions(Region::class)
                    ->setField('code')
                    ->setDisplay("name"),
                FilterType::select('city_id')
                    ->setIsLike(false)
                    ->setModelForOptions(City::class)
                    ->setDisplay("name"),
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
                    ->setHelpBlock("<small class='text-muted'>Наименование региона</small>")
                    ->setRequired(true),
                FormField::bselect('region_code', 'Регион')
                    ->setHelpBlock("<small class='text-muted'>Регион нахождения нас. пункта</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Region::class)
                    ->setField('code')
                    ->setDisplay('name'),
                FormField::bselect('city_id', 'Город терминала')
                    ->setHelpBlock("<small class='text-muted'>Город, через который происходит доставка</small>")
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('distance', 'Расстояние')
                    ->setHelpBlock("<small class='text-muted'>Фиксированное расстояние, если 0 -- расчитывается через Яндекс.Карты</small>")
                    ->setType('number')
                    ->setRequired(true)
            ])
        ]);

        return $form;
    }


}