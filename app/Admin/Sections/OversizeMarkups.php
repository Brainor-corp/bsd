<?php

namespace App\Admin\Sections;

use App\Oversize;
use App\Type;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Request;


class OversizeMarkups extends Section {
    protected $title = 'Множитель перегруза';
    protected $model = '\App\OversizeMarkup';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('oversize.real_name', 'Компания'),
            Column::text('rate.name', 'Мера'),
            Column::text('value', 'Значение'),
            Column::text('threshold', 'Предел'),
            Column::text('markup', 'Множитель'),
        ])
            ->setFilter([
                FilterType::select('oversize_id')
                    ->setModelForOptions(Oversize::class)
                    ->setDisplay('real_name'),
                FilterType::select('rate_id')
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($q){
                        return $q->where('class', 'rates');
                    })
                    ->setDisplay('name'),
                null,
                null,
                null,
            ])
            ->setPagination(10);

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