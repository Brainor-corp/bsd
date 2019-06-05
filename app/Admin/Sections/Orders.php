<?php

namespace App\Admin\Sections;

use App\City;
use App\Order;
use App\Type;
use App\User;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
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
            Column::text('id', '#'),
            Column::text('real_status', 'Статус'),
            Column::text('ship_city_name', 'Город отправки'),
            Column::text('dest_city_name', 'Город доставки'),
            Column::text('comprehensible_take_need', 'Нужно забрать'),
            Column::text('comprehensible_delivery_need', 'Нужно доставить'),
            Column::text('total_price', 'Сумма'),
            Column::text('code_1c', 'Код 1с'),
            Column::text('order_date', 'Дата заказа'),
            Column::text('order_finish_date', 'Дата завершения заказа'),
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
                FormField::select('status_id', 'Статус заказа')
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'order_status');
                    })
                    ->setDisplay('name'),
                FormField::select('ship_city_id', 'Город отправки')
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::select('dest_city_id', 'Город доставки')
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),

                FormField::select('take_need', 'Нужно забрать')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::select('take_in_city', 'Забор в городе')
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('take_address', 'Адрес забора'),
                FormField::input('take_distance', 'Расстояние забора (км)')->setType('number'),
                FormField::select('take_point', 'Точный забор')
                    ->setOptions([0=>'Нет', 1=>'Да']),
//                FormField::datepicker('take_time', 'Время забора')
//                    ->setTodayBtn(true)
//                    ->setFormat('yyyy-mm-dd hh:ii:ss')
//                    ->setLanguage('ru')
//                    ->setClearBtn(true),
                FormField::input('take_price', 'Цена забора'),

                FormField::select('delivery_need', 'Нужно доставить')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::select('delivery_in_city', 'Доставка в город')
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('delivery_address', 'Адрес доставки'),
                FormField::input('delivery_distance', 'Расстояние доставки (км)')->setType('number'),
                FormField::select('delivery_point', 'Точная доставка')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::datepicker('estimated_delivery_date', 'Плановая дата доставки') //todo убрать время из календарика
                    ->setTodayBtn(true)
                    ->setFormat('yyyy-mm-dd')
                    ->setLanguage('ru')
                    ->setClearBtn(true),
//                FormField::datepicker('delivery_time', 'Время доставки')
//                    ->setTodayBtn(true)
//                    ->setFormat('yyyy-mm-dd hh:ii:ss')
//                    ->setLanguage('ru')
//                    ->setClearBtn(true),
                FormField::input('delivery_price', 'Цена доствки'),
//                FormField::input('delivered_in', 'Доставлено')->setType('number'),
            ], 'col-lg-6 col-12'),
            FormColumn::column([
                FormField::input('total_price', 'Сумма'),

                FormField::select('sender_id', 'Отправитель(зарегестрированный)')
                    ->setModelForOptions(User::class)
                    ->setDisplay('full_name'),
                FormField::input('sender_name', 'Имя отправителя'),
                FormField::input('sender_phone', 'Телефон отправителя'),

                FormField::select('recepient_id', 'Получатель(зарегестрированный)')
                    ->setModelForOptions(User::class)
                    ->setDisplay('full_name'),
                FormField::input('recepient_name', 'Имя получателя'),
                FormField::input('recepient_phone', 'Телефон получателя'),

                FormField::select('payer_id', 'Плательщик(зарегестрированный)')
                    ->setModelForOptions(User::class)
                    ->setDisplay('full_name'),
                FormField::input('payer_name', 'Имя плательщика'),
                FormField::input('payer_phone', 'Телефон плательщика'),

                FormField::input('code_1c', 'Код 1с'),

                FormField::select('manager_id', 'Менеджер')
                    ->setModelForOptions(User::class)
                    ->setQueryFunctionForModel(function ($user){
                        return $user->whereHas('roles', function ($role){
                            return $role->where('slug', 'menedzher');// todo нормальные слаги ролей
                        });
                    })
                    ->setDisplay('full_name'),
                FormField::select('operator_id', 'Оператор')
                    ->setModelForOptions(User::class)
                    ->setQueryFunctionForModel(function ($user){
                        return $user->whereHas('roles', function ($role){
                            return $role->where('slug', 'operator');// todo нормальные слаги ролей
                        });
                    })
                    ->setDisplay('full_name'),

                FormField::datepicker('order_date', 'Дата заказа')
                    ->setTodayBtn(true)
                    ->setFormat('yyyy-mm-dd hh:ii:ss')
                    ->setLanguage('ru')
                    ->setClearBtn(true),
                FormField::datepicker('order_finish_date', 'Дата окончания заказа')
                    ->setTodayBtn(true)
                    ->setFormat('yyyy-mm-dd hh:ii:ss')
                    ->setLanguage('ru')
                    ->setClearBtn(true),
            ], 'col-lg-6 col-12'),
            FormColumn::column([
                FormField::custom(View::make('admin.orders.order-items')->with(compact('order'))->render()),
                FormField::custom(View::make('admin.orders.order-services')->with(compact('order'))->render()),
            ], 'col-12')
        ]);

        return $form;
    }

    public function afterSave(Request $request, $model = null) {
        $model->ship_city_name = City::where('id', $request->ship_city_id)->first()->name;
        $model->dest_city_name = City::where('id', $request->dest_city_id)->first()->name;

        $model->update();
    }
}