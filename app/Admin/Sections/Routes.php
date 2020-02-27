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

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('name', 'Название'),
            Column::text('shipCity.name', 'Город отправления'),
            Column::text('destinationCity.name', 'Город назначения'),
            Column::text('min_cost', 'Мин. стоимость'),
            Column::text('delivery_time', 'Срок доставки'),
            Column::text('comprehensive_show_in_price', 'Показ. в общем прайсе'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
                FilterType::bselect('ship_city_id')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setIsLike(false)
                    ->setModelForOptions(City::class)
                    ->setDisplay("name"),
                FilterType::bselect('dest_city_id')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setIsLike(false)
                    ->setModelForOptions(City::class)
                    ->setDisplay("name"),
                null,
                null,
                FilterType::bselect('show_in_price')
                    ->setOptions([
                        '0' => 'Нет',
                        '1' => 'Да'
                    ]),
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
        $priceListUrl = \route('pricesPage');

        $form = Form::panel([
            FormColumn::column([
                FormField::hidden('name', 'Название')->setValue('-'),
                FormField::bselect('ship_city_id', 'Город отправления')
                    ->setHelpBlock("<small class='text-muted'>Город отправления груза</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::bselect('dest_city_id', 'Город назначения')
                    ->setHelpBlock("<small class='text-muted'>Город назначения груза</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::bselect('base_route', 'Базовый тариф')
                    ->setHelpBlock("<small class='text-muted'>К базовому тарифу прибавляются фиксированные надбавки данного тарифа</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setModelForOptions(Route::class)
                    ->setDisplay('name'),
                FormField::input('min_cost', 'Минимальная стоимость доставки')
                    ->setHelpBlock("<small class='text-muted'>Минимальная стоимость доставки</small>")
                    ->setType('number'),
                FormField::input('wrapper_tariff', 'Тариф для бандероли')
                    ->setHelpBlock("<small class='text-muted'>Посылка не больше 2 кг по весу и 0,01 куб. м. по объёму</small>")
                    ->setType('number')
                    ->setRequired(true),
                FormField::input('delivery_time', 'Срок доставки')
                    ->setHelpBlock("<small class='text-muted'>Срок доставки в сутках</small>")
                    ->setType('number'),
                FormField::input('addition', 'Постоянная надбавка')
                    ->setHelpBlock("<small class='text-muted'>Не зависящая от характеристик груза (например, оформление документов на паром)</small>")
                    ->setType('number'),
                FormField::bselect('oversizes_id', 'Группа негабаритов')
                    ->setHelpBlock("<small class='text-muted'>Группа негабаритов для маршрута</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Oversize::class)
                    ->setDisplay('name'),
                FormField::bselect('fixed_tariffs', 'Фиксированные тарифы')
                    ->setHelpBlock("<small class='text-muted'>Например, для Гарант-Логистики</small>")
                    ->setOptions([0 => 'Нет', 1 => 'Да'])
                    ->setRequired(true),
                FormField::bselect('show_in_price', 'Показывать в общем прайсе')
                    ->setHelpBlock("<small class='text-muted'>Отображение маршрута на <a href='$priceListUrl' target='_blank'>странице прайс-листа</a></small>")
                    ->setOptions([0 => 'Нет', 1 => 'Да'])
                    ->setRequired(true),
                FormField::related('route_tariffs', 'Тарифы', RouteTariff::class, [
                    FormField::bselect('threshold_id', 'Предел')
                        ->setDataAttributes([
                            'data-live-search="true"'
                        ])
                        ->setRequired(true)
                        ->setModelForOptions(Threshold::class)
                        ->setDisplay('threshold_rate_value'),
                    FormField::input('price', 'Тариф')
                        ->setDataAttributes([
                            'step=any',
                            'min=0'
                        ])
                        ->setType('number'),
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
        ])->setCopyable(true);

        return $form;
    }

    public function beforeDelete(Request $request, $id = null)
    {
        RouteTariff::where('route_id', $id)->delete();
    }

    public function afterSave(Request $request, $model = null)
    {
        $model->name = $model->shipCity->name . ' → ' . $model->destinationCity->name;
        $model->save();
    }

}
