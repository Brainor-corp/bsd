<?php

namespace App\Admin\Sections;

use App\City;
use App\Oversize;
use App\Region;
use App\ReviewFile;
use App\Route;
use App\RouteTariff;
use App\Threshold;
use App\Type;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Display\Table\DisplayTable;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
//use Illuminate\Support\Facades\Request;
use Zeus\Admin\SectionBuilder\Meta\Meta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;


class Reviews extends Section {
    protected $title = 'Отзывы';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('author', 'ФИО или организация'),
            Column::text('email', 'Email'),
            Column::text('phone', 'Телефон'),
            Column::text('city.name', 'Город'),
            Column::text('comprehensible_moderate', 'Прошел модерацию?'),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {
        $file = ReviewFile::where('review_id', $id)->first();
        $form = Form::panel([
            FormColumn::column([
                FormField::input('author', 'ФИО или организация'),
                FormField::input('email', 'Email'),
                FormField::input('phone', 'Телефон')->setRequired(true),
                FormField::textarea('text', 'Текст отзыва'),
                FormField::select('city_id', 'Город')
                    ->setModelForOptions(City::class)
                    ->setDisplay('name')
                    ->setRequired(true),
                FormField::select('moderate', 'Прошел модерацию?')
                    ->setOptions([0 => 'Нет', 1 => 'Да'])
                    ->setRequired(true),
                FormField::custom(View::make('admin.reviews-section.reviews-file-field')->with(compact('file'))),
            ])
        ]);

        return $form;
    }
}