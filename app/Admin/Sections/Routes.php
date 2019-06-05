<?php

namespace App\Admin\Sections;

use App\City;
use App\Oversize;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Request;


class Routes extends Section
{
    protected $title = 'Маршруты';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('shipCity.name', 'Город отправки'),
            Column::text('destinationCity.name', 'Город назначения'),
            Column::text('min_cost', 'Мин. стоимость'),
            Column::text('delivery_time', 'Время доставки'),
            Column::text('addition', 'Доп.'),
            Column::text('oversize.name', 'Перегрузка'),
            Column::text('wrapper_tariff', 'Оберточный тариф'),
            Column::text('fixed_tariffs', 'Фикс. тариф'),
            Column::text('coefficient', 'Коэфф.'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
                FilterType::select('ship_city_id')
                    ->setIsLike(false)
                    ->setModelForOptions(City::class)
                    ->setDisplay("name"),
                FilterType::select('dest_city_id')
                    ->setIsLike(false)
                    ->setModelForOptions(City::class)
                    ->setDisplay("name"),
                null,
                null,
                null,
                FilterType::select('oversizes_id')
                    ->setIsLike(false)
                    ->setModelForOptions(Oversize::class)
                    ->setDisplay("name"),
                null,
                null,
                null,
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
                FormField::input('name', 'Название')->setRequired(true),
                FormField::select('ship_city_id', 'Город отправки')
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::select('dest_city_id', 'Город назначения')
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('min_cost', 'Мин. стоимость')->setType('number'),
                FormField::input('delivery_time', 'Время доставки')->setType('number'),
                FormField::input('base_route', 'Базовый маршрут')->setType('number'),
                FormField::input('addition', 'Доп.')->setType('number'),
                FormField::input('wrapper_tariff', 'Оберточный тариф')->setType('number')->setRequired(true),
                FormField::input('fixed_tariffs', 'Фикс. тариф')->setType('number')->setRequired(true),
                FormField::input('coefficient', 'Коэфф.')->setType('number')->setRequired(true),
                FormField::select('oversizes_id', 'Перегрузка')
                    ->setRequired(true)
                    ->setModelForOptions(Oversize::class)
                    ->setDisplay('name'),
            ])
        ]);

        return $form;
    }

}