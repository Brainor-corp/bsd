<?php

namespace App\Http\Controllers;

use App\City;
use App\ContactEmail;
use App\Counterparty;
use App\Http\Helpers\CalculatorHelper;
use App\Http\Helpers\EventHelper;
use App\Jobs\SendOrderCreatedMailToAdmin;
use App\Order;
use App\OrderItem;
use App\Polygon;
use App\Route;
use App\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class OrderController extends Controller
{
    public function orderSave(Request $request)
    {
        $request->merge([
            'sender_phone_legal' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->get('sender_phone_legal')),
            'sender_phone_individual' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->get('sender_phone_individual')),
            'recipient_phone_legal' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->get('recipient_phone_legal')),
            'recipient_phone_individual' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->get('recipient_phone_individual')),
            'payer_phone_legal' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->get('payer_phone_legal')),
            'payer_phone_individual' => str_replace(array('+', ' ', '(' , ')', '-'), '', $request->get('payer_phone_individual'))
        ]);

        $rules = [
            "ship_city" => ['required', 'string', 'max:255'],                                   // Город_отправления (название)
            "take_city_name" => ['nullable', 'string', 'max:255'],                              // Название_города_экспедиции (забор)
            "take_distance" => ['nullable', 'numeric'],                                         // Дистанция_экспедиции (забор)
            "ship_point" => ['nullable', 'string'],                                             // Адрес_экспедиции (забор)
            "take_polygon" => ['nullable'],                                                     // Полигон экспедиции (забор)

            "dest_city" => ['required', 'string', 'max:255'],                                   // Город_назначения (название)
            "bring_city_name" => ['nullable', 'string', 'max:255'],                             // Название_города_экспедиции (доставка)
            "bring_distance" => ['nullable', 'numeric'],                                        // Дистанция_экспедиции (доставка)
            "dest_point" => ['nullable', 'string'],                                             // Адрес_экспедиции (доставка)
            "bring_polygon" => ['nullable'],                                                    // Полигон экспедиции (доставка)

            "insurance_amount" => ['required', 'numeric', 'min:50000'],                         // Страховка (Цена_страхования)
            "discount" => ['nullable', 'numeric'],                                              // Скидка (Процент_скидки)

            "sender_type_id" => ['required', 'numeric'],                                        // Отправитель (Тип_контрагента)
            "sender_legal_form" => ['nullable', 'string', 'max:255'],                           // Отправитель (Правовая_форма)
            "sender_company_name" => ['nullable', 'string', 'min:3', 'max:255'],                // Отправитель (Наименование)
            "sender_legal_address_city" => ['nullable', 'string', 'max:255'],                   // Отправитель (Адрес (Город))
            "sender_legal_address_street" => ['nullable', 'string', 'max:255'],                 // Отправитель (Адрес (Улица))
            "sender_legal_address_house" => ['nullable', 'string', 'max:255'],                  // Отправитель (Адрес (Дом))
            "sender_legal_address_block" => ['nullable', 'string', 'max:255'],                  // Отправитель (Адрес (Корпус))
            "sender_legal_address_building" => ['nullable', 'string', 'max:255'],               // Отправитель (Адрес (Строение))
            "sender_legal_address_apartment" => ['nullable', 'string', 'max:255'],              // Отправитель (Адрес (Квартира_офис))
            "sender_inn" => ['nullable', 'string', 'max:12'],                                   // Отправитель (ИНН)
            "sender_kpp" => ['nullable', 'string', 'max:9'],                                    // Отправитель (КПП)
            "sender_contact_person_legal" => ['nullable', 'string', 'max:255'],                 // Отправитель (Контактное_лицо) -- Для юр.лиц
            "sender_phone_legal" => ['nullable', 'regex:/\d{11}/'],                             // Отправитель (Телефон) -- Для юр.лиц
            "sender_addition_info_legal" => ['nullable', 'string'],                             // Отправитель (Дополнительная_информация) -- Для юр.лиц
            "sender_name_individual" => ['nullable', 'string', 'max:255'],                      // Отправитель (Имя)
            "sender_passport_series" => ['nullable', 'numeric', 'digits:4'],                    // Отправитель (Серия_паспорта)
            "sender_passport_number" => ['nullable', 'numeric', 'digits:6'],                    // Отправитель (Номер_паспорта)
            "sender_contact_person_individual" => ['nullable', 'string', 'max:255'],            // Отправитель (Контактное_лицо) -- Для физ.лиц
            "sender_phone_individual" => ['nullable', 'regex:/\d{11}/'],                        // Отправитель (Телефон) -- Для физ.лиц
            "sender_addition_info_individual" => ['nullable', 'string'],                        // Отправитель (Дополнительная_информация) -- Для физ.лиц

            "recipient_type_id" => ['required', 'numeric'],                                     // Получатель (Тип_контрагента)
            "recipient_legal_form" => ['nullable', 'string', 'max:255'],                        // Получатель (Правовая_форма)
            "recipient_company_name" => ['nullable', 'string', 'min:3', 'max:255'],             // Получатель (Наименование)
            "recipient_legal_address_city" => ['nullable', 'string', 'max:255'],                // Получатель (Адрес (Город))
            "recipient_legal_address_street" => ['nullable', 'string', 'max:255'],              // Получатель (Адрес (Улица))
            "recipient_legal_address_house" => ['nullable', 'string', 'max:255'],               // Получатель (Адрес (Дом))
            "recipient_legal_address_block" => ['nullable', 'string', 'max:255'],               // Получатель (Адрес (Корпус))
            "recipient_legal_address_building" => ['nullable', 'string', 'max:255'],            // Получатель (Адрес (Строение))
            "recipient_legal_address_apartment" => ['nullable', 'string', 'max:255'],           // Получатель (Адрес (Квартира_офис))
            "recipient_inn"  => ['nullable', 'string', 'max:12'],                               // Получатель (ИНН)
            "recipient_kpp" => ['nullable', 'string', 'max:9'],                                 // Получатель (КПП)
            "recipient_contact_person_legal" => ['nullable', 'string', 'max:255'],              // Получатель (Контактное_лицо) -- Для юр.лиц
            "recipient_phone_legal" => ['nullable', 'regex:/\d{11}/'],                          // Получатель (Телефон) -- Для юр.лиц
            "recipient_addition_info_legal" => ['nullable', 'string'],                          // Получатель (Дополнительная_информация) -- Для юр.лиц
            "recipient_name_individual" => ['nullable', 'string', 'max:255'],                   // Получатель (Имя)
            "recipient_passport_series" => ['nullable', 'numeric', 'digits:4'],                 // Получатель (Серия_паспорта)
            "recipient_passport_number" => ['nullable', 'numeric', 'digits:6'],                 // Получатель (Номер_паспорта)
            "recipient_contact_person_individual" => ['nullable', 'string', 'max:255'],         // Получатель (Контактное_лицо) -- Для физ.лиц
            "recipient_phone_individual" => ['nullable', 'regex:/\d{11}/'],                     // Получатель (Телефон) -- Для физ.лиц
            "recipient_addition_info_individual" => ['nullable', 'string'],                     // Получатель (Дополнительная_информация) -- Для физ.лиц

            "payer_type" => ['required', 'string'],                                             // Данные плательщика
            "payer-email" => ['required', 'email'],                                             // Email_плательщика
            "payer_form_type_id" => ['nullable', 'numeric'],                                    // Плательщик (Тип_контрагента)
            "payer_legal_form" => ['nullable', 'string', 'max:255'],                            // Плательщик (Правовая_форма)
            "payer_company_name" => ['nullable', 'string', 'min:3', 'max:255'],                 // Плательщик (Наименование)
            "payer_legal_address_city" => ['nullable', 'string', 'max:255'],                    // Плательщик (Адрес (Город))
            "payer_legal_address_street" => ['nullable', 'string', 'max:255'],                  // Плательщик (Адрес (Улица))
            "payer_legal_address_house"  => ['nullable', 'string', 'max:255'],                  // Плательщик (Адрес (Дом))
            "payer_legal_address_block" => ['nullable', 'string', 'max:255'],                   // Плательщик (Адрес (Корпус))
            "payer_legal_address_building" => ['nullable', 'string', 'max:255'],                // Плательщик (Адрес (Строение))
            "payer_legal_address_apartment" => ['nullable', 'string', 'max:255'],               // Плательщик (Адрес (Квартира_офис))
            "payer_inn" => ['nullable', 'string', 'max:12'],                                    // Плательщик (ИНН)
            "payer_kpp" => ['nullable', 'string', 'max:9'],                                     // Плательщик (КПП)
            "payer_contact_person_legal" => ['nullable', 'string', 'max:255'],                  // Плательщик (Контактное_лицо) -- Для юр.лиц
            "payer_phone_legal" => ['nullable', 'regex:/\d{11}/'],                              // Плательщик (Телефон) -- Для юр.лиц
            "payer_addition_info_legal" => ['nullable', 'string'],                              // Плательщик (Дополнительная_информация) -- Для юр.лиц
            "payer_name_individual" => ['nullable', 'string', 'max:255'],                       // Плательщик (Имя)
            "payer_passport_series" => ['nullable', 'numeric', 'digits:4'],                     // Плательщик (Серия_паспорта)
            "payer_passport_number" => ['nullable', 'numeric', 'digits:6'],                     // Плательщик (Номер_паспорта)
            "payer_contact_person_individual" => ['nullable', 'string', 'max:255'],             // Плательщик (Контактное_лицо) -- Для физ.лиц
            "payer_phone_individual" => ['nullable', 'regex:/\d{11}/'],                         // Плательщик (Телефон) -- Для физ.лиц
            "payer_addition_info_individual" => ['nullable', 'string'],                         // Плательщик (Дополнительная_информация) -- Для физ.лиц

            "payment" => ['required', 'string'],                                                // Способ_оплаты
            "status" => ['required', 'string'],                                                 // Статус_заказа
            "order-creator" => ['required', 'string'],                                          // Заявку_заполнил
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cities = City::whereIn('name', [
            $request->get('ship_city'),
            $request->get('dest_city')
        ])->get();

        if(count($cities) < 2) {
            return abort(500, "Город(а) маршрута не найден(ы).");
        }

        $totalWeight = 0;
        $totalVolume = 0;

        $packages = [];
        foreach($request->get('cargo')['packages'] as $package) {
            $packages[] = new OrderItem([
                'length' => $package['length'],
                'width' => $package['width'],
                'height' => $package['height'],
                'volume' => $package['volume'],
                'weight' => $package['weight'],
                'quantity' => $package['quantity'],
            ]);

            $totalWeight += $package['weight'] * $package['quantity'];
            $totalVolume += $package['volume'] * $package['quantity'];
        }

        $calculatedData = CalculatorHelper::getAllCalculatedData(
            $cities->where('name', $request->get('ship_city'))->first(),
            $cities->where('name', $request->get('dest_city'))->first(),
            $request->get('cargo')['packages'],
            $request->get('service'),
            $request->get('need-to-take') === "on" ?
                [
                    'cityName' => $request->get('need-to-take-type') === "in" ?
                        $cities->where('name', $request->get('ship_city'))->first()->name :
                        $request->get('take_city_name'),
                    'weight' => $totalWeight,
                    'volume' => $totalVolume,
                    'isWithinTheCity' => $request->get('need-to-take-type') === "in",
                    'x2' => $request->get('ship-from-point') === "on",
                    'distance' => $request->get('take_distance'),
                    'polygonId' => $request->get('take_polygon')
                ] : [],
            $request->get('need-to-bring') === "on" ?
                [
                    'cityName' => $request->get('need-to-bring-type') === "in" ?
                        $cities->where('name', $request->get('dest_city'))->first()->name :
                        $request->get('bring_city_name'),
                    'weight' => $totalWeight,
                    'volume' => $totalVolume,
                    'isWithinTheCity' => $request->get('need-to-bring-type') === "in",
                    'x2' => $request->get('bring-to-point') === "on",
                    'distance' => $request->get('bring_distance'),
                    'polygonId' => $request->get('bring_polygon')
                ] : [],
            $request->get('insurance_amount'),
            $request->get('discount')
        );

        if(!$calculatedData) {
            return abort(400);
        }


        $allTypes = Type::where('class', 'payer_type')
            ->orWhere('class', 'payment_type')
            ->orWhere(function ($q) {
                return $q->where('class', 'order_status')
                    ->whereIn('slug', ['chernovik', 'ozhidaet-moderacii']);
            })
            ->get();

        $payerType = $allTypes->where('class', 'payer_type')
                ->where('slug', $request->get('payer_type'))
                ->first() ?? false;

        if(!$payerType) {
            return abort(500, 'Тип плательщика не найден.');
        }

        $orderStatus = $allTypes->where('class', 'order_status')
                ->where('slug', $request->get('status'))
                ->first() ?? false;

        if(!$orderStatus) {
            return abort(500, 'Статус заказа не найден.');
        }

        $paymentType = $allTypes->where('class', 'payment_type')
                ->where('slug', $request->get('payment'))
                ->first() ?? false;

        if(!$paymentType) {
            return abort(500, 'Тип оплаты не найден.');
        }

        $order = null;
        if($request->get('order_id')) {
            $order = Order::where('id', $request->get('order_id'))
                ->where(function ($orderQuery) {
                    return Auth::check() ? $orderQuery
                        ->where('user_id', Auth::user()->id)
                        ->orWhere('enter_id', $_COOKIE['enter_id']) :
                        $orderQuery->where('enter_id', $_COOKIE['enter_id']);
                })->firstOrFail();
        }

        $shipCity = $cities->where('name', $request->get('ship_city'))->first();
        $destCity = $cities->where('name', $request->get('dest_city'))->first();

        $takePolygon = null;
        if(!empty($request->get('take_polygon')) && $request->get('take_polygon') !== 0) {
            $takePolygon = Polygon::where([
                ['id', $request->get('take_polygon')],
                ['city_id', $shipCity->id]
            ])->firstOrFail();
        }

        $bringPolygon = null;
        if(!empty($request->get('bring_polygon')) && $request->get('bring_polygon') !== 0) {
            $bringPolygon = Polygon::where([
                ['id', $request->get('bring_polygon')],
                ['city_id', $destCity->id]
            ])->firstOrFail();
        }

        $route = Route::where([
            ['ship_city_id', $shipCity->id],
            ['dest_city_id', $destCity->id],
        ])->firstOrFail();
        $userTypes = Type::where('class', 'UserType')->get();

        if(!isset($order)) {
            $order = new Order;
        }

        $cargoType = Type::where('id', $request->get('cargo')['name'])->first();
        if($cargoType){
            $cargoTypeName = $cargoType->name;
            $cargoTypeId = $cargoType->id;
        }else{
            $cargoTypeName = 'Неизвестный тип груза';
            $cargoTypeId = null;
        }
        $order->total_price = $calculatedData['total'];
        $order->base_price = $calculatedData['route']['price'];
        $order->shipping_name = $cargoTypeName;
        $order->cargo_type = $cargoTypeId;
        $order->total_weight = $totalWeight;
        $order->ship_city_id = $shipCity->id;
        $order->ship_city_name = $shipCity->name;
        $order->take_polygon_id = $takePolygon->id ?? null;
        $order->dest_city_id = $destCity->id;
        $order->bring_polygon_id = $bringPolygon->id ?? null;
        $order->estimated_delivery_date = Carbon::now()->addDays($route->delivery_time)->toDateString();
        $order->dest_city_name = $destCity->name;
        $order->take_need = $request->get('need-to-take') === "on"; // Нужен ли забор груза
        $order->delivery_need = $request->get('need-to-bring') === "on"; // Нужна ли доставка груза

        // Получатель ///////////////////////////////////////////////////////////////////////////
        $senderType = $userTypes->where('id', $request->get('sender_type_id'))->first();
        $order->sender_type_id = $senderType->id;

        if($senderType->slug === 'fizicheskoe-lico') {
            $order->sender_name = $request->get('sender_name_individual');
            $order->sender_phone = $request->get('sender_phone_individual');
            $order->sender_addition_info = $request->get('sender_addition_info_individual');
            $order->sender_contact_person = $request->get('sender_contact_person_individual');

            $order->sender_passport_series = $request->get('sender_passport_series');
            $order->sender_passport_number = $request->get('sender_passport_number');

            if(
                Auth::check() &&
                !Counterparty::where([
                    ['user_id', Auth::id()],
                    ['name', $request->get('sender_name_individual')],
                ])->exists()
            ) {
                $counterparty = new Counterparty;
                $counterparty->type_id = $senderType->id;
                $counterparty->user_id = Auth::id();

                $counterparty->phone = $request->get('sender_phone_individual');
                $counterparty->contact_person = $request->get('sender_contact_person_individual');
                $counterparty->addition_info = $request->get('sender_addition_info_individual');

                $counterparty->name = $request->get('sender_name_individual');
                $counterparty->passport_series = $request->get('sender_passport_series');
                $counterparty->passport_number = $request->get('sender_passport_number');

                $counterparty->save();
            }
        } elseif($senderType->slug === 'yuridicheskoe-lico') {
            $order->sender_phone = $request->get('sender_phone_legal');
            $order->sender_addition_info = $request->get('sender_addition_info_legal');

            $order->sender_legal_form = $request->get('sender_legal_form');
            $order->sender_company_name = $request->get('sender_company_name');
            $order->sender_legal_address_city = $request->get('sender_legal_address_city');
            $order->sender_legal_address_street = $request->get('sender_legal_address_street');
            $order->sender_legal_address_house = $request->get('sender_legal_address_house');
            $order->sender_legal_address_block = $request->get('sender_legal_address_block');
            $order->sender_legal_address_building = $request->get('sender_legal_address_building');
            $order->sender_legal_address_apartment = $request->get('sender_legal_address_apartment');
            $order->sender_contact_person = $request->get('sender_contact_person_legal');
            $order->sender_inn = $request->get('sender_inn');
            $order->sender_kpp = $request->get('sender_kpp');

            if(
                Auth::check() &&
                !Counterparty::where([
                    ['user_id', Auth::id()],
                    ['inn', $request->get('sender_inn')],
                ])->exists()
            ) {
                $counterparty = new Counterparty;
                $counterparty->type_id = $senderType->id;
                $counterparty->user_id = Auth::id();

                $counterparty->phone = $request->get('sender_phone_legal');
                $counterparty->contact_person = $request->get('sender_contact_person_legal');
                $counterparty->addition_info = $request->get('sender_addition_info_legal');

                $counterparty->legal_form = $request->get('sender_legal_form');
                $counterparty->company_name = $request->get('sender_company_name');
                $counterparty->legal_address_city = $request->get('sender_legal_address_city');
                $counterparty->legal_address_street = $request->get('sender_legal_address_street');
                $counterparty->legal_address_house = $request->get('sender_legal_address_house');
                $counterparty->legal_address_block = $request->get('sender_legal_address_block');
                $counterparty->legal_address_building = $request->get('sender_legal_address_building');
                $counterparty->legal_address_apartment = $request->get('sender_legal_address_apartment');
                $counterparty->inn = $request->get('sender_inn');
                $counterparty->kpp = $request->get('sender_kpp');

                $counterparty->save();
            }
        } else {
            abort(500);
        }

        // Отправитель //////////////////////////////////////////////////////////////////////////
        $recipientType = $userTypes->where('id', $request->get('recipient_type_id'))->first();
        $order->recipient_type_id = $recipientType->id;

        if($recipientType->slug === 'fizicheskoe-lico') {
            $order->recipient_name = $request->get('recipient_name_individual');
            $order->recipient_phone = $request->get('recipient_phone_individual');
            $order->recipient_addition_info = $request->get('recipient_addition_info_individual');
            $order->recipient_contact_person = $request->get('recipient_contact_person_individual');

            $order->recipient_passport_series = $request->get('recipient_passport_series');
            $order->recipient_passport_number = $request->get('recipient_passport_number');

            if(
                Auth::check() &&
                !Counterparty::where([
                    ['user_id', Auth::id()],
                    ['name', $request->get('recipient_name_individual')],
                ])->exists()
            ) {
                $counterparty = new Counterparty;
                $counterparty->type_id = $recipientType->id;
                $counterparty->user_id = Auth::id();

                $counterparty->phone = $request->get('recipient_phone_individual');
                $counterparty->contact_person = $request->get('recipient_contact_person_individual');
                $counterparty->addition_info = $request->get('recipient_addition_info_individual');

                $counterparty->name = $request->get('recipient_name_individual');
                $counterparty->passport_series = $request->get('recipient_passport_series');
                $counterparty->passport_number = $request->get('recipient_passport_number');

                $counterparty->save();
            }
        } elseif($recipientType->slug === 'yuridicheskoe-lico') {
            $order->recipient_phone = $request->get('recipient_phone_legal');
            $order->recipient_addition_info = $request->get('recipient_addition_info_legal');

            $order->recipient_legal_form = $request->get('recipient_legal_form');
            $order->recipient_company_name = $request->get('recipient_company_name');
            $order->recipient_legal_address_city = $request->get('recipient_legal_address_city');
            $order->recipient_legal_address_street = $request->get('recipient_legal_address_street');
            $order->recipient_legal_address_house = $request->get('recipient_legal_address_house');
            $order->recipient_legal_address_block = $request->get('recipient_legal_address_block');
            $order->recipient_legal_address_building = $request->get('recipient_legal_address_building');
            $order->recipient_legal_address_apartment = $request->get('recipient_legal_address_apartment');
            $order->recipient_contact_person = $request->get('recipient_contact_person_legal');
            $order->recipient_inn = $request->get('recipient_inn');
            $order->recipient_kpp = $request->get('recipient_kpp');

            if(
                Auth::check() &&
                !Counterparty::where([
                    ['user_id', Auth::id()],
                    ['inn', $request->get('recipient_inn')],
                ])->exists()
            ) {
                $counterparty = new Counterparty;
                $counterparty->type_id = $recipientType->id;
                $counterparty->user_id = Auth::id();

                $counterparty->phone = $request->get('recipient_phone_legal');
                $counterparty->contact_person = $request->get('recipient_contact_person_legal');
                $counterparty->addition_info = $request->get('recipient_addition_info_legal');

                $counterparty->legal_form = $request->get('recipient_legal_form');
                $counterparty->company_name = $request->get('recipient_company_name');
                $counterparty->legal_address_city = $request->get('recipient_legal_address_city');
                $counterparty->legal_address_street = $request->get('recipient_legal_address_street');
                $counterparty->legal_address_house = $request->get('recipient_legal_address_house');
                $counterparty->legal_address_block = $request->get('recipient_legal_address_block');
                $counterparty->legal_address_building = $request->get('recipient_legal_address_building');
                $counterparty->legal_address_apartment = $request->get('recipient_legal_address_apartment');
                $counterparty->inn = $request->get('recipient_inn');
                $counterparty->kpp = $request->get('recipient_kpp');

                $counterparty->save();
            }
        } else {
            abort(500);
        }

        // Плательщик ///////////////////////////////////////////////////////////////////////////
        $order->payer_type = $payerType->id;
        $order->payer_email = $request->get('payer-email');

        if($payerType->slug === '3-e-lico') {
            $payerFormType = $userTypes->where('id', $request->get('payer_form_type_id'))->first();
            $order->payer_form_type_id = $payerFormType->id;

            if($payerFormType->slug === 'fizicheskoe-lico') {
                $order->payer_name = $request->get('payer_name_individual');
                $order->payer_phone = $request->get('payer_phone_individual');
                $order->payer_addition_info = $request->get('payer_addition_info_individual');
                $order->payer_contact_person = $request->get('payer_contact_person_individual');

                $order->payer_passport_series = $request->get('payer_passport_series');
                $order->payer_passport_number = $request->get('payer_passport_number');

                if(
                    Auth::check() &&
                    !Counterparty::where([
                        ['user_id', Auth::id()],
                        ['name', $request->get('payer_name_individual')],
                    ])->exists()
                ) {
                    $counterparty = new Counterparty;
                    $counterparty->type_id = $payerFormType->id;
                    $counterparty->user_id = Auth::id();

                    $counterparty->phone = $request->get('payer_phone_individual');
                    $counterparty->contact_person = $request->get('payer_contact_person_individual');
                    $counterparty->addition_info = $request->get('payer_addition_info_individual');

                    $counterparty->name = $request->get('payer_name_individual');
                    $counterparty->passport_series = $request->get('payer_passport_series');
                    $counterparty->passport_number = $request->get('payer_passport_number');

                    $counterparty->save();
                }
            } elseif($payerFormType->slug === 'yuridicheskoe-lico') {
                $order->payer_phone = $request->get('payer_phone_legal');
                $order->payer_addition_info = $request->get('payer_addition_info_legal');

                $order->payer_legal_form = $request->get('payer_legal_form');
                $order->payer_company_name = $request->get('payer_company_name');
                $order->payer_legal_address_city = $request->get('payer_legal_address_city');
                $order->payer_legal_address_street = $request->get('payer_legal_address_street');
                $order->payer_legal_address_house = $request->get('payer_legal_address_house');
                $order->payer_legal_address_block = $request->get('payer_legal_address_block');
                $order->payer_legal_address_building = $request->get('payer_legal_address_building');
                $order->payer_legal_address_apartment = $request->get('payer_legal_address_apartment');
                $order->payer_contact_person = $request->get('payer_contact_person_legal');
                $order->payer_inn = $request->get('payer_inn');
                $order->payer_kpp = $request->get('payer_kpp');

                if(
                    Auth::check() &&
                    !Counterparty::where([
                        ['user_id', Auth::id()],
                        ['inn', $request->get('payer_inn')],
                    ])->exists()
                ) {
                    $counterparty = new Counterparty;
                    $counterparty->type_id = $payerFormType->id;
                    $counterparty->user_id = Auth::id();

                    $counterparty->phone = $request->get('payer_phone_legal');
                    $counterparty->contact_person = $request->get('payer_contact_person_legal');
                    $counterparty->addition_info = $request->get('payer_addition_info_legal');

                    $counterparty->legal_form = $request->get('payer_legal_form');
                    $counterparty->company_name = $request->get('payer_company_name');
                    $counterparty->legal_address_city = $request->get('payer_legal_address_city');
                    $counterparty->legal_address_street = $request->get('payer_legal_address_street');
                    $counterparty->legal_address_house = $request->get('payer_legal_address_house');
                    $counterparty->legal_address_block = $request->get('payer_legal_address_block');
                    $counterparty->legal_address_building = $request->get('payer_legal_address_building');
                    $counterparty->legal_address_apartment = $request->get('payer_legal_address_apartment');
                    $counterparty->inn = $request->get('payer_inn');
                    $counterparty->kpp = $request->get('payer_kpp');

                    $counterparty->save();
                }
            } else {
                abort(500);
            }
        } else { // Если плательщик -- отправитель/получатель, то занулим его данные.
            $order->payer_name = null;
            $order->payer_phone = null;
            $order->payer_addition_info = null;
            $order->payer_contact_person = null;
            $order->payer_legal_form = null;
            $order->payer_company_name = null;
            $order->payer_legal_address_city = null;
            $order->payer_legal_address_street = null;
            $order->payer_legal_address_house = null;
            $order->payer_legal_address_block = null;
            $order->payer_legal_address_building = null;
            $order->payer_legal_address_apartment = null;
            $order->payer_contact_person = null;
            $order->payer_inn = null;
            $order->payer_kpp = null;
        }

        $order->discount = $request->get('discount');
        $order->discount_amount = $calculatedData['discount'] ?? 0;
        $order->insurance = $request->get('insurance_amount');
        $order->insurance_amount = end($calculatedData['services'])['total'] ?? 0;
        $order->user_id = Auth::user()->id ?? null;
        $order->enter_id = $_COOKIE['enter_id'];
        $order->payment_type = $paymentType->id;
        $order->status_id = $orderStatus->id;
        $order->order_date = Carbon::now();
        $order->order_creator = $request->get('order-creator');

        if($request->get('need-to-take') === "on") {
            $order->take_point = $request->get('ship-from-point') === "on";
            $order->take_in_city = $request->get('need-to-take-type') === "in";

            // Если забор груза за пределами города
            if($request->get('need-to-take-type') === "from") {
                $order->take_address = $request->get('ship_point'); // Адрес забора
                $order->take_city_name = $calculatedData['delivery']['take']['city_name']; // Город забора
                $order->take_distance = $calculatedData['delivery']['take']['distance'] ?? null; // Дистанция от города отправки до адреса забора
            } else { // Если забор в пределах города
                $order->take_address = null;
                $order->take_distance = null;
                $order->take_city_name = $shipCity->name; // Город забора
            }

            $order->take_price = $calculatedData['delivery']['take']['price']; // Цена забора
        } else {
            $order->take_point = false;
            $order->take_in_city = false;
            $order->take_address = null;
            $order->take_city_name = null;
            $order->take_distance = null;
            $order->take_price = null;
        }

        if($request->get('need-to-bring') === "on") {
            $order->delivery_point = $request->get('bring-to-point') === "on";
            $order->delivery_in_city = $request->get('need-to-bring-type') === "in";

            // Если доставка за пределами города
            if($request->get('need-to-bring-type') === "from") {
                $order->delivery_address = $request->get('dest_point'); // Адрес доставки
                $order->delivery_city_name = $calculatedData['delivery']['bring']['city_name']; // Город доставки
                $order->delivery_distance = $calculatedData['delivery']['bring']['distance'] ?? null; // Дистанция от города назначения до адреса доставки
            } else { // Если доставка в пределах города
                $order->delivery_address = null;
                $order->delivery_distance = null;
                $order->delivery_city_name = $destCity->name; // Город забора
            }

            $order->delivery_price = $calculatedData['delivery']['bring']['price']; // Цена доставки
        } else {
            $order->delivery_point = false;
            $order->delivery_in_city = false;
            $order->delivery_address = null;
            $order->delivery_city_name = null;
            $order->delivery_distance = null;
            $order->delivery_price = null;
        }

        $servicesToSync = [];
        foreach($calculatedData['services'] as $service) {
            if(!isset($service['id'])) {
                continue;
            }

            $servicesToSync[$service['id']] = [
                'price' => $service['total']
            ];
        }

        $order->save();

        $order->order_items()->delete();
        $order->order_items()->saveMany($packages);

        $order->order_services()->sync($servicesToSync);

        if(Auth::check()) {
            EventHelper::createEvent(
                'Заказ успешно зарегистрирован!',
                null,
                1,
                '/klientam/report/'.$order->id,
                Auth::id()
            );
        }

        foreach(ContactEmail::where('active', true)->get() as $email) {
            SendOrderCreatedMailToAdmin::dispatch($email->email, $order);
        }

        return $order->status->slug === "chernovik" ?
            redirect(route('calculator-show', ['id' => $order->id])) :
//            redirect(route('report-show', ['id' => $order->id]));
            redirect(route('orders-list'));
    }

    public function shipmentSearch(Request $request){
        $order = Order::with('status')
            ->whereDoesntHave('status', function ($statusQuery) {
                return $statusQuery->where('slug', "chernovik");
            })
            ->find($request->get('order_id'));

        return View::make('v1.pages.shipment-status.status-page')->with(compact('order'));
    }

    public function searchOrders(Request $request) {
        $orders = Order::with('status', 'ship_city', 'dest_city')
            ->where(function ($ordersQuery) {
                return Auth::check() ? $ordersQuery
                    ->where('user_id', Auth::user()->id)
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $ordersQuery->where('enter_id', $_COOKIE['enter_id']);
            })
            ->when($request->get('id'), function ($order) use ($request) {
                return $order->where('id', 'LIKE', '%' . $request->get('id') . '%');
            })
            ->when($request->finished == 'true', function ($order) use ($request) {
                return $order->whereHas('status', function ($type) {
                    return $type->where('slug', 'dostavlen');
                });
            })
            ->when($request->finished == 'false' && $request->get('status'), function ($order) use ($request) {
                return $order->where('status_id', $request->get('status'));
            })
            ->get();

        return View::make('v1.partials.profile.orders')->with(compact('orders'))->render();
    }

    public function actionGetOrderItems(Request $request) {
        $order = Order::where('id', $request->order_id)
            ->where(function ($orderQuery) {
                return Auth::check() ? $orderQuery
                    ->where('user_id', Auth::user()->id)
                    ->orWhere('enter_id', $_COOKIE['enter_id']) :
                    $orderQuery->where('enter_id', $_COOKIE['enter_id']);
            })
            ->with('order_items')->firstOrFail();
        return View::make('v1.partials.profile.order-items-modal-body')->with(compact('order'))->render();
    }

    public function actionGetOrderSearchInput() {
        $types = Type::where('class', 'order_status')->get();
        return View::make('v1.partials.profile.order-search-select')->with(compact('types'))->render();
    }
}
