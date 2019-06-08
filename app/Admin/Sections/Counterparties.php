<?php

namespace App\Admin\Sections;

use App\Counterparty;
use App\Type;
use App\User;
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
    protected $title = 'Counterparties';

    public static function onDisplay(){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('type.name', 'Тип'),
            Column::text('user.full_name', 'Пользователь'),
            Column::text('title', 'Название'),
            Column::text('active_for_human', 'Активность'),
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
            null,
            FilterType::select('active', 'Активация')
                ->setOptions([
                    0 => 'Не активен',
                    1 => 'Активен'
                ]),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate()
    {
        return self::onEdit(null);
    }

    public static function onEdit($id)
    {
        $model = Counterparty::where('id', $id)->with('type')->firstOrFail();
        $form = null;

        switch($model->type->slug) {
            case 'fizicheskoe-lico':
                $form = Form::panel([
                    FormColumn::column([
                        FormField::select('active', 'Активность')
                            ->setOptions([
                                0 => "Не активен",
                                1 => "Активен"
                            ])
                            ->setRequired(true),
                        FormField::select('type_id', 'Тип')
                            ->setModelForOptions(Type::class)
                            ->setField('id')
                            ->setQueryFunctionForModel(function ($q) {
                                return $q->where('class', 'UserType');
                            })
                            ->setDisplay('name')
                            ->setRequired(true),
                        FormField::select('user_id', 'Пользователь')
                            ->setModelForOptions(User::class)
                            ->setField('id')
                            ->setDisplay('full_name')
                            ->setRequired(true),
                        FormField::input('name', 'ФИО')->setRequired(true),
                        FormField::input('passport_series', 'Серия паспорта')->setRequired(true),
                        FormField::input('passport_number', 'Номер паспорта')->setRequired(true),
                        FormField::input('contact_person', 'Контактное лицо')->setRequired(true),
                        FormField::input('phone', 'Телефон')->setRequired(true),
                        FormField::input('addition_info', 'Дополнительная информация')->setRequired(true),
                    ])
                ]);

                break;

            case 'yuridicheskoe-lico':
                $form = Form::panel([
                    FormColumn::column([
                        FormField::select('active', 'Активность')
                            ->setOptions([
                                0 => "Не активен",
                                1 => "Активен"
                            ])
                            ->setRequired(true),
                        FormField::select('type_id', 'Тип')
                            ->setModelForOptions(Type::class)
                            ->setField('id')
                            ->setQueryFunctionForModel(function ($q) {
                                return $q->where('class', 'UserType');
                            })
                            ->setDisplay('name')
                            ->setRequired(true),
                        FormField::select('user_id', 'Пользователь')
                            ->setModelForOptions(User::class)
                            ->setField('id')
                            ->setDisplay('full_name')
                            ->setRequired(true),
                        FormField::input('contact_person', 'Контактное лицо')->setRequired(true),
                        FormField::input('phone', 'Телефон')->setRequired(true),
                        FormField::input('inn', 'ИНН')->setRequired(true),
                        FormField::input('kpp', 'КПП')->setRequired(true),
                        FormField::input('addition_info', 'Дополнительная информация')->setRequired(true),
                    ]),
                    FormColumn::column([
                        FormField::input('legal_form', 'Правовая форма')->setRequired(true),
                        FormField::input('company_name', 'Название организации')->setRequired(true),
                        FormField::input('legal_address_city', 'Город')->setRequired(true),
                        FormField::input('legal_address_street', 'Улица')->setRequired(true),
                        FormField::input('legal_address_house', 'Дом')->setRequired(true),
                        FormField::input('legal_address_block', 'Корпус')->setRequired(true),
                        FormField::input('legal_address_building', 'Строение')->setRequired(true),
                        FormField::input('legal_address_apartment', 'Квартира/офис')->setRequired(true),
                    ])
                ]);
                break;
            default: return abort(404);
        }

        return $form;
    }


}