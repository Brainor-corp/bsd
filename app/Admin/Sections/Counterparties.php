<?php

namespace App\Admin\Sections;

use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class Counterparties extends Section
{
    protected $title = 'Контрагенты';

    protected $checkAccess = true;

    public static function onDisplay(){
        $display = Display::table([
            Column::link('id', '#'),
            Column::text('type.name', 'Тип'),
            Column::text('title', 'Название'),
            Column::text('active_for_human', 'Активность'),
            Column::text('code_1c', 'Идентификатор 1с'),
        ])->setFilter([
            null,
            FilterType::select('type_id', 'Тип')
                ->setModelForOptions(Type::class)
                ->setQueryFunctionForModel(function ($q) {
                    return $q->where('class', 'UserType');
                })
                ->setIsLike(false)
                ->setDisplay('name'),
            null,
            FilterType::select('active', 'Активация')
                ->setOptions([
                    0 => 'Не активен',
                    1 => 'Активен'
                ]),
            FilterType::text('code_1c', 'Идентификатор 1с')
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
                FormField::bselect('active', 'Активность')
                    ->setOptions([
                        0 => "Не активен",
                        1 => "Активен"
                    ])
                    ->setRequired(true),
                FormField::bselect('type_id', 'Тип')
                    ->setModelForOptions(Type::class)
                    ->setField('id')
                    ->setQueryFunctionForModel(function ($q) {
                        return $q->where('class', 'UserType');
                    })
                    ->setDisplay('name')
                    ->setRequired(true),
                FormField::input('name', 'ФИО'),
                FormField::input('passport_series', 'Серия паспорта'),
                FormField::input('passport_number', 'Номер паспорта'),
                FormField::input('contact_person', 'Контактное лицо'),
                FormField::input('phone', 'Телефон'),
                FormField::input('addition_info', 'Дополнительная информация'),
                FormField::input('inn', 'ИНН'),
                FormField::input('kpp', 'КПП'),
                FormField::input('legal_form', 'Правовая форма'),
                FormField::input('company_name', 'Название организации'),
                FormField::input('legal_address_city', 'Город'),
                FormField::input('legal_address_street', 'Улица'),
                FormField::input('legal_address_house', 'Дом'),
                FormField::input('legal_address_block', 'Корпус'),
                FormField::input('legal_address_building', 'Строение'),
                FormField::input('legal_address_apartment', 'Квартира/офис'),
            ])
        ]);

        return $form;
    }

    public function beforeDelete(Request $request, $id = null)
    {
        DB::table('counterparty_user')->where('counterparty_id', $id)->delete();
    }
}