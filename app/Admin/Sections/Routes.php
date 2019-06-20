<?php

namespace App\Admin\Sections;

use App\City;
use App\Oversize;
use App\Route;
use App\RouteTariff;
use App\Threshold;
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
                FormField::hidden('name', 'Название')->setValue('-'),
                FormField::bselect('ship_city_id', 'Город отправки')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::bselect('dest_city_id', 'Город назначения')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('min_cost', 'Мин. стоимость')->setType('number'),
                FormField::input('delivery_time', 'Время доставки')->setType('number'),
                FormField::bselect('base_route', 'Базовый маршрут')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setModelForOptions(Route::class)
                    ->setDisplay('name'),
                FormField::input('addition', 'Доп.')->setType('number'),
                FormField::input('wrapper_tariff', 'Оберточный тариф')->setType('number')->setRequired(true),
                FormField::bselect('fixed_tariffs', 'Фикс. тариф')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setOptions([0 => 'Нет', 1 => 'Да'])
                    ->setRequired(true),
                FormField::input('coefficient', 'Коэфф.')->setType('number')->setRequired(true),
                FormField::bselect('oversizes_id', 'Перегрузка')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Oversize::class)
                    ->setDisplay('name'),
                FormField::related('route_tariffs', 'Тарифы', RouteTariff::class, [
                    FormField::bselect('threshold_id', 'Предел')
                        ->setDataAttributes([
                            'data-live-search="true"'
                        ])
                        ->setRequired(true)
                        ->setModelForOptions(Threshold::class)
                        ->setDisplay('threshold_rate_value'),
                    FormField::input('price', 'Тариф')->setType('number'),
                    FormField::bselect('rate_id', 'Мера')
                        ->setDataAttributes([
                            'data-live-search="true"'
                        ])
                        ->setRequired(true)
                        ->setModelForOptions(Type::class)
                        ->setQueryFunctionForModel(function ($query) {
                            return $query->where('class', 'rates');
                        })
                        ->setDisplay('name'),
                ])
            ])
        ]);

        return $form;
    }

    public function afterSave(Request $request, $model = null)
    {
        $model->name = $model->shipCity->name . ' → ' . $model->destinationCity->name;
        $model->save();
    }

}