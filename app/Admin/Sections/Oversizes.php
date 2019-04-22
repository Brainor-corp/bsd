<?php

namespace App\Admin\Sections;

use App\City;
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


class Oversizes extends Section {
    protected $title = 'Перегруз';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('length', 'Длина'),
            Column::text('width', 'Ширина'),
            Column::text('height', 'Высота'),
            Column::text('volume', 'Обьем'),
            Column::text('weight', 'Вес'),
            Column::text('ratio', 'Пропорция'),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {

        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Название')->setRequired(true),
                FormField::input('length', 'Длина')->setType('number')->setRequired(true),
                FormField::input('width', 'Ширина')->setType('number')->setRequired(true),
                FormField::input('height', 'Высота')->setType('number')->setRequired(true),
                FormField::input('volume', 'Обьем')->setType('number')->setRequired(true),
                FormField::input('weight', 'Вес')->setType('number')->setRequired(true),
                FormField::input('ratio', 'Пропорция')->setType('number')->setRequired(true),
            ])
        ]);

        return $form;
    }
}