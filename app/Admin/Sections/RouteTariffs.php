<?php

namespace App\Admin\Sections;

use App\Route;
use App\RouteTariff;
use App\Threshold;
use App\Type;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Bradmin\SectionBuilder\Meta\Meta;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Request;


class RouteTariffs extends Section {
    protected $title = 'Тарифы маршрутов';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('real_route', 'Маршрут'),
            Column::text('real_rate', 'Мера'),
            Column::text('real_threshold', 'Значение'),
            Column::text('price', 'Цена'),
        ])
            ->setFilter([
                null,
                FilterType::select('route_id')
                    ->setIsLike(false)
                    ->setModelForOptions(Route::class)
                    ->setDisplay("name"),
                FilterType::select('rate_id')
                    ->setIsLike(false)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($q) {
                        return $q->where('class', 'rates');
                    })
                    ->setDisplay("name"),
                FilterType::select('threshold_id')
                    ->setIsLike(false)
                    ->setModelForOptions(Threshold::class)
                    ->setDisplay("value"),
                null,
            ])
            ->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {
        $rate = RouteTariff::where('id', $id)->first();

        $meta = new Meta;
        $meta->setScripts([
            'body' => [
                asset('v1/js/admin/route-tariffs.js')
            ]
        ]);

        $form = Form::panel([
            FormColumn::column([
                FormField::input('price', 'Цена')->setType('number'),
                FormField::select('route_id', 'Маршрут')
                    ->setModelForOptions(Route::class)
                    ->setDisplay('name'),
                FormField::select('rate_id', 'Мера')
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query) {
                        return $query->where('class', 'rates');
                    })
                    ->setDisplay('name'),
                FormField::select('threshold_id', 'Значение')
                    ->setRequired(true)
                    ->setModelForOptions(Threshold::class)
                    ->setQueryFunctionForModel(function ($query) use ($rate){
                        return $query->when(isset($rate), function ($q) use ($rate){
                            return $q->where('rate_id', $rate->rate_id);
                        });
                    })
                    ->setDisplay('value'),
            ])
        ])
        ->setMeta($meta);

        return $form;
    }
}