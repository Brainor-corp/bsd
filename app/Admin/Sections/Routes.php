<?php

namespace App\Admin\Sections;

use App\City;
use App\Oversize;
use App\Region;
use App\Route;
use App\Type;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Display\Table\DisplayTable;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
//use Illuminate\Support\Facades\Request;
use Bradmin\SectionBuilder\Meta\Meta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;


class Routes extends Section
{
    protected $title = 'Маршруты';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('min_cost', 'Мин. стоимость'),
            Column::text('delivery_time', 'Время доставки'),
            Column::text('addition', 'Доп.'),
            Column::text('real_oversizes', 'Перегрузка'),
            Column::text('wrapper_tariff', 'Оберточный тариф'),
            Column::text('fixed_tariffs', 'Фикс. тариф'),
            Column::text('coefficient', 'Коэфф.'),
        ])->setPagination(10);

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
                FormField::input('name', 'Название')->setValue('.')->setReadonly(true),
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

    public function afterSave(Request $request, $model = null) {
        $route = Route::where('id', $model->id)->first();
        $shipCity = City::where('id', $request->ship_city_id)->first();
        $destCity = City::where('id', $request->dest_city_id)->first();

        $route->name = $shipCity->name . ' → ' . $destCity->name;
        $route->update();
    }
}