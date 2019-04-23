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


class OversizeMarkups extends Section {
    protected $title = 'Множитель перегруза';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('oversize.real_name', 'Компания'),
            Column::text('rate.name', 'Мера'),
            Column::text('value', 'Значение'),
            Column::text('threshold', 'Предел'),
            Column::text('markup', 'Множитель'),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {

        $form = Form::panel([
            FormColumn::column([
                FormField::select('oversize_id', 'Компания')
                    ->setRequired(true)
                    ->setModelForOptions(Oversize::class)
                    ->setDisplay('name'),
                FormField::select('rate_id', 'Мера')
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'rates');
                    })
                    ->setDisplay('name'),
                FormField::input('value', 'Значение')->setRequired(true)->setType('number'),
                FormField::input('threshold', 'Предел')->setRequired(true)->setType('number'),
                FormField::input('markup', 'Множитель')->setRequired(true)->setType('number'),
            ])
        ]);

        return $form;
    }
}