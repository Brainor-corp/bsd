<?php

namespace App\Admin\Sections;

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
use Zeus\Admin\SectionBuilder\Meta\Meta;

//use Illuminate\Support\Facades\Request;


class RouteTariffs extends Section {
    protected $title = 'Тарифы маршрутов';
    protected $model = '\App\RouteTariff';

    protected $checkAccess = true;

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::link('id', '#'),
            Column::text('route.name', 'Маршрут'),
            Column::text('rate.name', 'Мера'),
            Column::text('threshold.value', 'Значение'),
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
                FormField::input('price', 'Цена')
                    ->setDataAttributes([
                        'step=any',
                        'min=0'
                    ])
                    ->setType('number'),
                FormField::bselect('route_id', 'Маршрут')
                    ->setModelForOptions(Route::class)
                    ->setDisplay('name'),
                FormField::bselect('rate_id', 'Мера')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query) {
                        return $query->where('class', 'rates');
                    })
                    ->setDisplay('name'),
                FormField::bselect('threshold_id', 'Значение')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
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