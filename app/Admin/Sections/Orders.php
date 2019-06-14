<?php

namespace App\Admin\Sections;

use App\City;
use App\Order;
use App\Type;
use App\User;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

//use Illuminate\Support\Facades\Request;


class Orders extends Section {
    protected $title = 'Заказы';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#')->setSortable(1),
            Column::text('real_status', 'Статус'),
            Column::text('ship_city_name', 'Город отправки'),
            Column::text('dest_city_name', 'Город доставки'),
            Column::text('total_price', 'Сумма'),
            Column::text('code_1c', 'Код 1с'),
            Column::text('order_date', 'Дата заказа')->setSortable(1),
        ])->setFilter([
            FilterType::text('id', '#'),
            null,
            null,
            null,
            null,
            FilterType::text('code_1c', 'Код 1с'),
            null,
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {
        $order = isset($id) ? Order::whereId($id)->with([
            'order_items.type',
            'order_services' => function ($servicesQuery) {
                return $servicesQuery->withPivot('price');
            }
        ])->first() : null;

//        $meta = new Meta;
//        $meta->setScripts([
//            'body' => [
//                asset('v1/js/admin/orders.js')
//            ]
//        ]);

        $form = Form::panel([
            FormColumn::column([
                FormField::custom('<h4>Основное</h4><hr>'),
                FormField::input('shipping_name', 'Название груза'),
                FormField::select('status_id', 'Статус заказа')
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'order_status');
                    })->setDisplay('name'),
                FormField::select('user_id', 'Пользователь')
                    ->setModelForOptions(User::class)
                    ->setDisplay('name'),
                FormField::select('ship_city_id', 'Город отправления')
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::select('dest_city_id', 'Город доставки')
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('total_weight', 'Общий вес')->setType('number'),
                FormField::input('total_price', 'Итоговая цена')->setType('number'),
                FormField::input('base_price', 'Цена маршрута')->setType('number'),

                FormField::custom('<h4>Страховка</h4><hr>'),
                FormField::input('insurance', 'Страховая сумма')->setType('number'),
                FormField::input('insurance_amount', 'Цена страховки')->setType('number'),

                FormField::custom('<h4>Скидка</h4><hr>'),
                FormField::input('discount', 'Процент')->setType('number'),
                FormField::input('discount_amount', 'Сумма')->setType('number'),

                FormField::custom('<h4>Забор груза</h4><hr>'),
                FormField::select('take_need', 'Нужно забрать')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::select('take_in_city', 'Забор в пределах города')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::input('take_address', 'Адрес забора'),
                FormField::input('take_distance', 'Расстояние забора (км)')->setType('number'),
                FormField::select('take_point', 'Точный забор')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::input('take_price', 'Цена забора')->setType('number'),

                FormField::custom('<h4>Доставка груза</h4><hr>'),
                FormField::select('delivery_need', 'Нужно забрать')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::select('delivery_in_city', 'Забор в пределах города')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::input('delivery_address', 'Адрес забора'),
                FormField::input('delivery_distance', 'Расстояние забора (км)')->setType('number'),
                FormField::select('delivery_point', 'Точный забор')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::input('delivery_price', 'Цена доставки')->setType('number'),

                FormField::custom('<h4>Отправитель</h4><hr>'),
                FormField::select('sender_type_id', 'Тип')
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query) {
                        return $query->where('class', 'UserType');
                    })
                    ->setDisplay('name'),
                FormField::input('sender_legal_form', 'Правовая форма'),
                FormField::input('sender_company_name', 'Название компании'),
                FormField::input('sender_inn', 'ИНН'),
                FormField::input('sender_kpp', 'КПП'),
                FormField::input('sender_name', 'ФИО'),
                FormField::input('sender_phone', 'Телефон'),
                FormField::input('sender_contact_person', 'Контактное лицо'),
                FormField::input('sender_passport_series', 'Серия паспорта'),
                FormField::input('sender_passport_number', 'Номер паспорта'),
                FormField::input('sender_addition_info', 'Доп. информация'),
                FormField::custom('<strong>Юридический адрес</strong><hr>'),
                FormField::input('sender_legal_address_city', 'Город'),
                FormField::input('sender_legal_address_street', 'Улица'),
                FormField::input('sender_legal_address_house', 'Дом'),
                FormField::input('sender_legal_address_block', 'Блок'),
                FormField::input('sender_legal_address_building', 'Строение'),
                FormField::input('sender_legal_address_apartment', 'Квартира/офис'),

                FormField::custom('<h4>Получатель</h4><hr>'),
                FormField::select('recipient_type_id', 'Тип')
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query) {
                        return $query->where('class', 'UserType');
                    })
                    ->setDisplay('name'),
                FormField::input('recipient_legal_form', 'Правовая форма'),
                FormField::input('recipient_company_name', 'Название компании'),
                FormField::input('recipient_inn', 'ИНН'),
                FormField::input('recipient_kpp', 'КПП'),
                FormField::input('recipient_name', 'ФИО'),
                FormField::input('recipient_phone', 'Телефон'),
                FormField::input('recipient_contact_person', 'Контактное лицо'),
                FormField::input('recipient_passport_series', 'Серия паспорта'),
                FormField::input('recipient_passport_number', 'Номер паспорта'),
                FormField::input('recipient_addition_info', 'Доп. информация'),
                FormField::custom('<strong>Юридический адрес</strong><hr>'),
                FormField::input('recipient_legal_address_city', 'Город'),
                FormField::input('recipient_legal_address_street', 'Улица'),
                FormField::input('recipient_legal_address_house', 'Дом'),
                FormField::input('recipient_legal_address_block', 'Блок'),
                FormField::input('recipient_legal_address_building', 'Строение'),
                FormField::input('recipient_legal_address_apartment', 'Квартира/офис'),

                FormField::custom('<h4>Плательщик</h4><hr>'),
                FormField::select('payer_type', 'Лицо')
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query) {
                        return $query->where('class', 'payer_type');
                    })
                    ->setDisplay('name'),
                FormField::select('payer_form_type_id', 'Тип')
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query) {
                        return $query->where('class', 'UserType');
                    })
                    ->setDisplay('name'),
                FormField::input('payer_legal_form', 'Правовая форма'),
                FormField::input('payer_company_name', 'Название компании'),
                FormField::input('payer_inn', 'ИНН'),
                FormField::input('payer_kpp', 'КПП'),
                FormField::input('payer_name', 'ФИО'),
                FormField::input('payer_phone', 'Телефон'),
                FormField::input('payer_contact_person', 'Контактное лицо'),
                FormField::input('payer_passport_series', 'Серия паспорта'),
                FormField::input('payer_passport_number', 'Номер паспорта'),
                FormField::input('payer_addition_info', 'Доп. информация'),
                FormField::custom('<strong>Юридический адрес</strong><hr>'),
                FormField::input('payer_legal_address_city', 'Город'),
                FormField::input('payer_legal_address_street', 'Улица'),
                FormField::input('payer_legal_address_house', 'Дом'),
                FormField::input('payer_legal_address_block', 'Блок'),
                FormField::input('payer_legal_address_building', 'Строение'),
                FormField::input('payer_legal_address_apartment', 'Квартира/офис'),
            ])
        ]);

//        $form = Form::panel([
//            FormColumn::column([
//                FormField::select('status_id', 'Статус заказа')
//                    ->setModelForOptions(Type::class)
//                    ->setQueryFunctionForModel(function ($query){
//                        return $query->where('class', 'order_status');
//                    })
//                    ->setDisplay('name'),
//                FormField::select('ship_city_id', 'Город отправки')
//                    ->setModelForOptions(City::class)
//                    ->setDisplay('name'),
//                FormField::select('dest_city_id', 'Город доставки')
//                    ->setModelForOptions(City::class)
//                    ->setDisplay('name'),
//
//                FormField::select('take_need', 'Нужно забрать')
//                    ->setOptions([0=>'Нет', 1=>'Да']),
//                FormField::select('take_in_city', 'Забор в городе')
//                    ->setModelForOptions(City::class)
//                    ->setDisplay('name'),
//                FormField::input('take_address', 'Адрес забора'),
//                FormField::input('take_distance', 'Расстояние забора (км)')->setType('number'),
//                FormField::select('take_point', 'Точный забор')
//                    ->setOptions([0=>'Нет', 1=>'Да']),
////                FormField::datepicker('take_time', 'Время забора')
////                    ->setTodayBtn(true)
////                    ->setFormat('yyyy-mm-dd hh:ii:ss')
////                    ->setLanguage('ru')
////                    ->setClearBtn(true),
//                FormField::input('take_price', 'Цена забора'),
//
//                FormField::select('delivery_need', 'Нужно доставить')
//                    ->setOptions([0=>'Нет', 1=>'Да']),
//                FormField::select('delivery_in_city', 'Доставка в город')
//                    ->setModelForOptions(City::class)
//                    ->setDisplay('name'),
//                FormField::input('delivery_address', 'Адрес доставки'),
//                FormField::input('delivery_distance', 'Расстояние доставки (км)')->setType('number'),
//                FormField::select('delivery_point', 'Точная доставка')
//                    ->setOptions([0=>'Нет', 1=>'Да']),
//                FormField::datepicker('estimated_delivery_date', 'Плановая дата доставки') //todo убрать время из календарика
//                    ->setTodayBtn(true)
//                    ->setFormat('yyyy-mm-dd')
//                    ->setLanguage('ru')
//                    ->setClearBtn(true),
////                FormField::datepicker('delivery_time', 'Время доставки')
////                    ->setTodayBtn(true)
////                    ->setFormat('yyyy-mm-dd hh:ii:ss')
////                    ->setLanguage('ru')
////                    ->setClearBtn(true),
//                FormField::input('delivery_price', 'Цена доствки'),
////                FormField::input('delivered_in', 'Доставлено')->setType('number'),
//            ], 'col-lg-6 col-12'),
//            FormColumn::column([
//                FormField::input('total_price', 'Сумма'),
//
//                FormField::select('sender_id', 'Отправитель(зарегестрированный)')
//                    ->setModelForOptions(User::class)
//                    ->setDisplay('full_name'),
//                FormField::input('sender_name', 'Имя отправителя'),
//                FormField::input('sender_phone', 'Телефон отправителя'),
//
//                FormField::select('recepient_id', 'Получатель(зарегестрированный)')
//                    ->setModelForOptions(User::class)
//                    ->setDisplay('full_name'),
//                FormField::input('recepient_name', 'Имя получателя'),
//                FormField::input('recepient_phone', 'Телефон получателя'),
//
//                FormField::select('payer_id', 'Плательщик(зарегестрированный)')
//                    ->setModelForOptions(User::class)
//                    ->setDisplay('full_name'),
//                FormField::input('payer_name', 'Имя плательщика'),
//                FormField::input('payer_phone', 'Телефон плательщика'),
//
//                FormField::input('code_1c', 'Код 1с'),
//
//                FormField::select('manager_id', 'Менеджер')
//                    ->setModelForOptions(User::class)
//                    ->setQueryFunctionForModel(function ($user){
//                        return $user->whereHas('roles', function ($role){
//                            return $role->where('slug', 'menedzher');// todo нормальные слаги ролей
//                        });
//                    })
//                    ->setDisplay('full_name'),
//                FormField::select('operator_id', 'Оператор')
//                    ->setModelForOptions(User::class)
//                    ->setQueryFunctionForModel(function ($user){
//                        return $user->whereHas('roles', function ($role){
//                            return $role->where('slug', 'operator');// todo нормальные слаги ролей
//                        });
//                    })
//                    ->setDisplay('full_name'),
//
//                FormField::datepicker('order_date', 'Дата заказа')
//                    ->setTodayBtn(true)
//                    ->setFormat('yyyy-mm-dd hh:ii:ss')
//                    ->setLanguage('ru')
//                    ->setClearBtn(true),
//                FormField::datepicker('order_finish_date', 'Дата окончания заказа')
//                    ->setTodayBtn(true)
//                    ->setFormat('yyyy-mm-dd hh:ii:ss')
//                    ->setLanguage('ru')
//                    ->setClearBtn(true),
//            ], 'col-lg-6 col-12'),
//            FormColumn::column([
//                FormField::custom(View::make('admin.orders.order-items')->with(compact('order'))->render()),
//                FormField::custom(View::make('admin.orders.order-services')->with(compact('order'))->render()),
//            ], 'col-12')
//        ]);

        return $form;
    }

    public function afterSave(Request $request, $model = null) {
        $model->ship_city_name = City::where('id', $request->ship_city_id)->first()->name;
        $model->dest_city_name = City::where('id', $request->dest_city_id)->first()->name;

        $model->update();
    }

    public function isCreatable()
    {
        return false;
    }
}