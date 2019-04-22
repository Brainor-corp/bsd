<?php

namespace App\Admin\Sections;

use App\City;
use App\ForwardThreshold;
use App\Oversize;
use App\Region;
use App\Route;
use App\RouteTariff;
use App\Threshold;
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


class InsideForwarding extends Section {
    protected $title = 'Внутренние пересылки';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('real_city', 'Город'),
            Column::text('real_forward_threshold', 'Группа предельных порогов'),
            Column::text('tariff', 'Тариф'),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {

        $form = Form::panel([
            FormColumn::column([
                FormField::select('city_id', 'Город')
                    ->setRequired(true)
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::select('forward_threshold_id', 'Группа предельных порогов')
                    ->setRequired(true)
                    ->setModelForOptions(ForwardThreshold::class)
                    ->setDisplay('name'),
                FormField::input('tariff', 'Тариф')->setType('number')->setRequired(true),
            ])
        ]);

        return $form;
    }
}