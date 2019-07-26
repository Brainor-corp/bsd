<?php

namespace App\Admin\Sections;

use App\Oversize;
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


class OversizeMarkups extends Section {
    protected $title = 'Множитель перегруза';
    protected $model = '\App\OversizeMarkup';

    protected $checkAccess = true;

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::link('oversize.real_name', 'Компания'),
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
                FormField::bselect('oversize_id', 'Компания')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Oversize::class)
                    ->setDisplay('name'),
                FormField::bselect('rate_id', 'Мера')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
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