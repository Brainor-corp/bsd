<?php

namespace App\Admin\Sections;

use App\City;
use App\Oversize;
use App\Region;
use App\Route;
use App\RouteTariff;
use App\Threshold;
use App\Type;
use App\User;
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
        $form = Form::panel([
            FormColumn::column([
                FormField::select('status_id', 'Статус заказа')
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'order_status');
                    })
                    ->setDisplay('name'),
                FormField::select('ship_city_id', 'Город отправки')//aftersave
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::select('dest_city_id', 'Город доставки')//aftersave
                    ->setModelForOptions(City::class)
                    ->setDisplay('name'),

                FormField::select('take_need', 'Нужно забрать')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::select('take_in_city', 'Забор в городе')//aftersave
                ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('take_address', 'Адрес забора'),
                FormField::input('take_distance', 'Расстояние забора')->setType('number'),
                FormField::input('take_point', 'Пункт забора'),
                FormField::datepicker('take_time', 'Время забора'),
                FormField::input('take_price', 'Цена забора')->setType('number'),

                FormField::select('delivery_need', 'Нужно доставить')
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::select('delivery_in_city', 'Доставка в город')//aftersave
                ->setModelForOptions(City::class)
                    ->setDisplay('name'),
                FormField::input('delivery_address', 'Адрес доставки'),
                FormField::input('delivery_distance', 'Расстояние доставки')->setType('number'),
                FormField::input('delivery_point', 'Пункт доставки'),
                FormField::datepicker('delivery_time', 'Время доставки'),
                FormField::input('delivery_price', 'Цена доствки')->setType('number'),
                FormField::input('delivered_in', 'Доставлено')->setType('number'),

                FormField::input('total_price', 'Сумма')->setType('number'),

                FormField::select('sender_id', 'Отправитель')
                    ->setModelForOptions(User::class)
                    ->setDisplay('full_name'),
                FormField::input('sender_name', 'Имя отправителя'),
                FormField::input('sender_phone', 'Телефон отправителя'),

                FormField::select('recepient_id', 'Получатель')
                    ->setModelForOptions(User::class)
                    ->setDisplay('full_name'),
                FormField::input('recepient_name', 'Имя получателя'),
                FormField::input('recepient_phone', 'Телефон получателя'),

                FormField::select('payer_id', 'Плетельщик')
                    ->setModelForOptions(User::class)
                    ->setDisplay('full_name'),
                FormField::input('payer_name', 'Имя плательщика'),
                FormField::input('payer_phone', 'Телефон плательщика'),

            ])
        ]);

        return $form;
    }
}