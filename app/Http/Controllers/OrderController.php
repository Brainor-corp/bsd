<?php

namespace App\Http\Controllers;

use App\City;
use App\Counterparty;
use App\Events\OrderCreated;
use App\Http\Helpers\Api1CHelper;
use App\Http\Helpers\CalculatorHelper;
use App\Http\Helpers\EventHelper;
use App\Http\Requests\OrderFileUpload;
use App\Order;
use App\OrderItem;
use App\PendingFile;
use App\Polygon;
use App\Route;
use App\Rules\Discount1c;
use App\Rules\GoogleReCaptchaV2;
use App\Rules\INN;
use App\Rules\OrderFileRule;
use App\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            'g-recaptcha-response' => ['required', new GoogleReCaptchaV2()],
            "ship_city" => ['required', 'string', 'max:255'],                                   // Город_отправления (название)
            'take_driving_directions_file' => ['nullable', 'string', 'max:255', new OrderFileRule()],   // Схема проезда до города отправления
            "take_city_name" => ['nullable', 'string', 'max:255'],                              // Название_города_экспедиции (забор)
            "take_distance" => ['nullable', 'numeric'],                                         // Дистанция_экспедиции (забор)
            "ship_point" => ['nullable', 'string', 'max:150'],                                  // Адрес_экспедиции (забор)
            "take_polygon" => ['nullable'],                                                     // Полигон экспедиции (забор)

            "cargo.total_weight" => ['numeric', 'required', 'min:0'],                           // Общий вес
            "cargo.total_volume" => ['numeric', 'required', 'min:0'],                           // Общий объём
            "cargo.total_quantity" => ['integer', 'required'],                                  // Общее кол-во
            "cargo.packages.*.length" => ['nullable', 'numeric', 'min:0', 'max:12'],            // Длина пакета
            "cargo.packages.*.width" => ['nullable', 'numeric', 'min:0', 'max:2.5'],            // Ширина пакета
            "cargo.packages.*.height" => ['nullable', 'numeric', 'min:0', 'max:2.5'],           // Высота пакета
            "cargo.packages.*.weight" => ['nullable', 'numeric', 'min:0'],                      // Вес пакета
            "cargo.packages.*.quantity" => ['nullable', 'integer', 'min:0'],                    // Кол-во пакета

            "dest_city" => ['required', 'string', 'max:255'],                                   // Город_назначения (название)
            'delivery_driving_directions_file' => ['nullable', 'string', 'max:255', new OrderFileRule()],   // Схема проезда до города назначения
            "bring_city_name" => ['nullable', 'string', 'max:255'],                             // Название_города_экспедиции (доставка)
            "bring_distance" => ['nullable', 'numeric'],                                        // Дистанция_экспедиции (доставка)
            "dest_point" => ['nullable', 'string', 'max:150'],                                  // Адрес_экспедиции (доставка)
            "bring_polygon" => ['nullable'],                                                    // Полигон экспедиции (доставка)

            "discount" => ['nullable', 'numeric', new Discount1c()],                            // Скидка (Процент_скидки)

            "sender_type_id" => ['required', 'numeric'],                                        // Отправитель (Тип_контрагента)
            "sender_legal_form" => ['nullable', 'string', 'max:255'],                           // Отправитель (Правовая_форма)
            "sender_company_name" => ['nullable', 'string', 'min:3', 'max:50'],                 // Отправитель (Наименование)
            "sender_legal_address_city" => ['nullable', 'string', 'max:70'],                    // Отправитель (Адрес (Город))
            "sender_legal_address" => ['nullable', 'string', 'max:190'],                        // Отправитель (Адрес)
            "sender_inn" => ['nullable', 'string', 'max:12', new INN()],                        // Отправитель (ИНН)
            "sender_kpp" => ['nullable', 'string', 'max:9'],                                    // Отправитель (КПП)
            "sender_contact_person_legal" => ['nullable', 'string', 'max:255'],                 // Отправитель (Контактное_лицо) -- Для юр.лиц
            "sender_phone_legal" => ['nullable', 'regex:/\d{11}/'],                             // Отправитель (Телефон) -- Для юр.лиц
            "sender_addition_info_legal" => ['nullable', 'string', 'max:500'],                  // Отправитель (Дополнительная_информация) -- Для юр.лиц
            "sender_name_individual" => ['nullable', 'string', 'min:3', 'max:50'],              // Отправитель (Имя)
            "sender_passport_series" => ['nullable', 'numeric', 'digits:4'],                    // Отправитель (Серия_паспорта)
            "sender_passport_number" => ['nullable', 'numeric', 'digits:6'],                    // Отправитель (Номер_паспорта)
            "sender_contact_person_individual" => ['nullable', 'string', 'max:255'],            // Отправитель (Контактное_лицо) -- Для физ.лиц
            "sender_phone_individual" => ['nullable', 'regex:/\d{11}/'],                        // Отправитель (Телефон) -- Для физ.лиц
            "sender_addition_info_individual" => ['nullable', 'string', 'max:500'],             // Отправитель (Дополнительная_информация) -- Для физ.лиц

            "recipient_type_id" => ['required', 'numeric'],                                     // Получатель (Тип_контрагента)
            "recipient_legal_form" => ['nullable', 'string', 'max:255'],                        // Получатель (Правовая_форма)
            "recipient_company_name" => ['nullable', 'string', 'min:3', 'max:50'],              // Получатель (Наименование)
            "recipient_legal_address_city" => ['nullable', 'string', 'max:70'],                 // Получатель (Адрес (Город))
            "recipient_legal_address" => ['nullable', 'string', 'max:190'],                     // Получатель (Адрес)
            "recipient_inn"  => ['nullable', 'string', 'max:12', new INN()],                    // Получатель (ИНН)
            "recipient_kpp" => ['nullable', 'string', 'max:9'],                                 // Получатель (КПП)
            "recipient_contact_person_legal" => ['nullable', 'string', 'max:255'],              // Получатель (Контактное_лицо) -- Для юр.лиц
            "recipient_phone_legal" => ['nullable', 'regex:/\d{11}/'],                          // Получатель (Телефон) -- Для юр.лиц
            "recipient_addition_info_legal" => ['nullable', 'string', 'max:500'],               // Получатель (Дополнительная_информация) -- Для юр.лиц
            "recipient_name_individual" => ['nullable', 'string', 'min:3', 'max:50'],           // Получатель (Имя)
            "recipient_passport_series" => ['nullable', 'numeric', 'digits:4'],                 // Получатель (Серия_паспорта)
            "recipient_passport_number" => ['nullable', 'numeric', 'digits:6'],                 // Получатель (Номер_паспорта)
            "recipient_contact_person_individual" => ['nullable', 'string', 'max:255'],         // Получатель (Контактное_лицо) -- Для физ.лиц
            "recipient_phone_individual" => ['nullable', 'regex:/\d{11}/'],                     // Получатель (Телефон) -- Для физ.лиц
            "recipient_addition_info_individual" => ['nullable', 'string', 'max:500'],          // Получатель (Дополнительная_информация) -- Для физ.лиц

            "payer_type" => ['required', 'string'],                                             // Данные плательщика
            "payer-email" => ['required', 'email'],                                             // Email_плательщика
            "payer_form_type_id" => ['nullable', 'numeric'],                                    // Плательщик (Тип_контрагента)
            "payer_legal_form" => ['nullable', 'string', 'max:255'],                            // Плательщик (Правовая_форма)
            "payer_company_name" => ['nullable', 'string', 'min:3', 'max:50'],                  // Плательщик (Наименование)
            "payer_legal_address_city" => ['nullable', 'string', 'max:70'],                     // Плательщик (Адрес (Город))
            "payer_legal_address" => ['nullable', 'string', 'max:190'],                         // Плательщик (Адрес)
            "payer_inn" => ['nullable', 'string', 'max:12', new INN()],                         // Плательщик (ИНН)
            "payer_kpp" => ['nullable', 'string', 'max:9'],                                     // Плательщик (КПП)
            "payer_contact_person_legal" => ['nullable', 'string', 'max:255'],                  // Плательщик (Контактное_лицо) -- Для юр.лиц
            "payer_phone_legal" => ['nullable', 'regex:/\d{11}/'],                              // Плательщик (Телефон) -- Для юр.лиц
            "payer_addition_info_legal" => ['nullable', 'string', 'max:500'],                   // Плательщик (Дополнительная_информация) -- Для юр.лиц
            "payer_name_individual" => ['nullable', 'string', 'min:3', 'max:50'],               // Плательщик (Имя)
            "payer_passport_series" => ['nullable', 'numeric', 'digits:4'],                     // Плательщик (Серия_паспорта)
            "payer_passport_number" => ['nullable', 'numeric', 'digits:6'],                     // Плательщик (Номер_паспорта)
            "payer_contact_person_individual" => ['nullable', 'string', 'max:255'],             // Плательщик (Контактное_лицо) -- Для физ.лиц
            "payer_phone_individual" => ['nullable', 'regex:/\d{11}/'],                         // Плательщик (Телефон) -- Для физ.лиц
            "payer_addition_info_individual" => ['nullable', 'string', 'max:500'],              // Плательщик (Дополнительная_информация) -- Для физ.лиц

            "payment" => ['required', 'string'],                                                // Способ_оплаты
            "status" => ['required', 'string', 'in:chernovik,order_auth,order_guest'],          // Черновик|Заявка с авторизацей|Заявка без регистрации
            "order-creator" => ['required', 'string'],                                          // Заявку_заполнил,

            "order_date" => ['nullable', 'date'],                                               // Дата исполнения
            "ship_time_from" => ['nullable', 'date_format:H:i'],                                // Время исполнения (С)
            "ship_time_to" => ['nullable', 'date_format:H:i'],                                  // Время исполнения (По)
            "warehouse_schedule" => ['required', 'string', 'max:255'],                          // Режим работы склада
            "cargo_comment" => ['nullable', 'string', 'max:500'],                               // Примечания по грузу
            "type" => ['required', 'in:order,calculator'],                                      // Тип заявки (Заявка|Калькулятор)
        ];

        // Для неавторизованных пользователей скидка всегда нулевая
        if(Auth::guest()) {
            $request->merge(['discount' => 0]);
        }

        $messages = [
            'g-recaptcha-response.required'  => 'Подтвердите, что Вы не робот.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->sometimes('insurance_amount', 'required|numeric|min:50000', function ($request) {
            return !empty($request->get('insurance'));
        });
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

        $totalWeight = $request->get('cargo')['total_weight'] ?? 0;
        $totalVolume = $request->get('cargo')['total_volume'] ?? 0;
        $totalQuantity = $order->total_quantity ?? ($request->get('cargo')['total_quantity'] ?? 0);

        $packages = [];
        foreach($request->get('cargo')['packages'] as $package) {
            $packages[] = new OrderItem([
                'length' => $package['length'] ?? 0,
                'width' => $package['width'] ?? 0,
                'height' => $package['height'] ?? 0,
                'volume' => $package['volume'] ?? 0,
                'weight' => $package['weight'] ?? 0,
                'quantity' => $package['quantity'] ?? 0,
            ]);
        }

        $calculatedData = CalculatorHelper::getAllCalculatedData(
            $cities->where('name', $request->get('ship_city'))->first(),
            $cities->where('name', $request->get('dest_city'))->first(),
            $request->get('cargo')['packages'],
            $totalWeight,
            $totalVolume,
            $totalQuantity,
            $request->get('service'),
            $request->get('need-to-take') === "on" ?
                [
                    'baseCityName' => $cities->where('name', $request->get('ship_city'))->first()->name,
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
                    'baseCityName' => $cities->where('name', $request->get('dest_city'))->first()->name,
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
            $request->get('insurance_amount') ?? 0,
            $request->get('discount'),
            !empty($request->get('insurance'))
        );

        if(!$calculatedData) {
            return abort(400);
        }


        $allTypes = Type::where('class', 'payer_type')
            ->orWhere('class', 'payment_type')
            ->orWhere('class', 'OrderCreatorType')
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

        $status = 'chernovik';
        if(
            $request->get('status') === 'order_auth' ||
            $request->get('status') === 'order_guest'
        ) {
            $status = 'ozhidaet-moderacii';
        }

        $orderStatus = $allTypes->where('class', 'order_status')
                ->where('slug', $status)
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

        $orderCreatorType = $allTypes->where('class', 'OrderCreatorType')
                ->where('slug', $request->get('order-creator-type'))
                ->first() ?? false;

        if(!$orderCreatorType) {
            return abort(500, 'Тип заполнителя заявки не найден.');
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

        $paymentStatus = Type::where([
            ['class', 'OrderPaymentStatus'],
            ['slug', 'ne-oplachena'],
        ])->firstOrFail();

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
        $order->total_volume = $totalVolume;
        $order->total_quantity = $totalQuantity;
        $order->ship_city_id = $shipCity->id;
        $order->take_driving_directions_file = $request->get('take_driving_directions_file');
        $order->ship_city_name = $shipCity->name;
        $order->take_polygon_id = $takePolygon->id ?? null;
        $order->dest_city_id = $destCity->id;
        $order->delivery_driving_directions_file = $request->get('delivery_driving_directions_file');
        $order->bring_polygon_id = $bringPolygon->id ?? null;
        $order->estimated_delivery_date = Carbon::now()->addDays($route->delivery_time)->toDateString();
        $order->dest_city_name = $destCity->name;
        $order->take_need = $request->get('need-to-take') === "on"; // Нужен ли забор груза
        $order->delivery_need = $request->get('need-to-bring') === "on"; // Нужна ли доставка груза
        $order->order_creator_type = $orderCreatorType->id;

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
            Auth::check()
            ) {
                $counterparty = Counterparty::where([
                    ['hash_name', md5($request->get('sender_name_individual') . config('app.key'))],
                ])->first();

                if(!isset($counterparty)) {
                    $counterparty = new Counterparty;
                    $counterparty->type_id = $senderType->id;

                    $counterparty->phone = $request->get('sender_phone_individual');
                    $counterparty->contact_person = $request->get('sender_contact_person_individual');
                    $counterparty->addition_info = $request->get('sender_addition_info_individual');

                    $counterparty->name = $request->get('sender_name_individual');
                    $counterparty->passport_series = $request->get('sender_passport_series');
                    $counterparty->passport_number = $request->get('sender_passport_number');

                    $counterparty->hash_name = md5($request->get('sender_name_individual') . config('app.key'));

                    $counterparty->save();
                }

                Auth::user()->counterparties()->syncWithoutDetaching([$counterparty->id]);
            }
        } elseif($senderType->slug === 'yuridicheskoe-lico') {
            $order->sender_phone = $request->get('sender_phone_legal');
            $order->sender_addition_info = $request->get('sender_addition_info_legal');

            $order->sender_legal_form = $request->get('sender_legal_form');
            $order->sender_company_name = $request->get('sender_company_name');
            $order->sender_legal_address_city = $request->get('sender_legal_address_city');
            $order->sender_legal_address = $request->get('sender_legal_address');
            $order->sender_contact_person = $request->get('sender_contact_person_legal');
            $order->sender_inn = $request->get('sender_inn');
            $order->sender_kpp = $request->get('sender_kpp');

            if(
            Auth::check()
            ) {
                $counterparty = Counterparty::where([
                    ['hash_inn', md5($request->get('sender_inn') . config('app.key'))],
                ])->first();

                if(!isset($counterparty)) {
                    $counterparty = new Counterparty;
                    $counterparty->type_id = $senderType->id;

                    $counterparty->phone = $request->get('sender_phone_legal');
                    $counterparty->contact_person = $request->get('sender_contact_person_legal');
                    $counterparty->addition_info = $request->get('sender_addition_info_legal');

                    $counterparty->legal_form = $request->get('sender_legal_form');
                    $counterparty->company_name = $request->get('sender_company_name');
                    $counterparty->legal_address_city = $request->get('sender_legal_address_city');
                    $counterparty->legal_address = $request->get('sender_legal_address');
                    $counterparty->inn = $request->get('sender_inn');
                    $counterparty->kpp = $request->get('sender_kpp');

                    $counterparty->hash_inn = md5($request->get('sender_inn') . config('app.key'));

                    $counterparty->save();
                }

                Auth::user()->counterparties()->syncWithoutDetaching([$counterparty->id]);
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
            Auth::check()
            ) {
                $counterparty = Counterparty::where([
                    ['hash_name', md5($request->get('recipient_name_individual') . config('app.key'))],
                ])->first();

                if(!isset($counterparty)) {
                    $counterparty = new Counterparty;
                    $counterparty->type_id = $recipientType->id;

                    $counterparty->phone = $request->get('recipient_phone_individual');
                    $counterparty->contact_person = $request->get('recipient_contact_person_individual');
                    $counterparty->addition_info = $request->get('recipient_addition_info_individual');

                    $counterparty->name = $request->get('recipient_name_individual');
                    $counterparty->passport_series = $request->get('recipient_passport_series');
                    $counterparty->passport_number = $request->get('recipient_passport_number');

                    $counterparty->hash_name = md5($request->get('recipient_name_individual') . config('app.key'));

                    $counterparty->save();
                }

                Auth::user()->counterparties()->syncWithoutDetaching([$counterparty->id]);
            }
        } elseif($recipientType->slug === 'yuridicheskoe-lico') {
            $order->recipient_phone = $request->get('recipient_phone_legal');
            $order->recipient_addition_info = $request->get('recipient_addition_info_legal');

            $order->recipient_legal_form = $request->get('recipient_legal_form');
            $order->recipient_company_name = $request->get('recipient_company_name');
            $order->recipient_legal_address_city = $request->get('recipient_legal_address_city');
            $order->recipient_legal_address = $request->get('recipient_legal_address');
            $order->recipient_contact_person = $request->get('recipient_contact_person_legal');
            $order->recipient_inn = $request->get('recipient_inn');
            $order->recipient_kpp = $request->get('recipient_kpp');

            if(
            Auth::check()
            ) {
                $counterparty = Counterparty::where([
                    ['hash_inn', md5($request->get('recipient_inn') . config('app.key'))],
                ])->first();

                if(!isset($counterparty)) {
                    $counterparty = new Counterparty;
                    $counterparty->type_id = $recipientType->id;

                    $counterparty->phone = $request->get('recipient_phone_legal');
                    $counterparty->contact_person = $request->get('recipient_contact_person_legal');
                    $counterparty->addition_info = $request->get('recipient_addition_info_legal');

                    $counterparty->legal_form = $request->get('recipient_legal_form');
                    $counterparty->company_name = $request->get('recipient_company_name');
                    $counterparty->legal_address_city = $request->get('recipient_legal_address_city');
                    $counterparty->legal_address = $request->get('recipient_legal_address');
                    $counterparty->inn = $request->get('recipient_inn');
                    $counterparty->kpp = $request->get('recipient_kpp');

                    $counterparty->hash_inn = md5($request->get('recipient_inn') . config('app.key'));

                    $counterparty->save();
                }

                Auth::user()->counterparties()->syncWithoutDetaching([$counterparty->id]);
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
                Auth::check()
                ) {
                    $counterparty = Counterparty::where([
                        ['hash_name', md5($request->get('payer_name_individual') . config('app.key'))],
                    ])->first();

                    if(!isset($counterparty)) {
                        $counterparty = new Counterparty;
                        $counterparty->type_id = $payerFormType->id;

                        $counterparty->phone = $request->get('payer_phone_individual');
                        $counterparty->contact_person = $request->get('payer_contact_person_individual');
                        $counterparty->addition_info = $request->get('payer_addition_info_individual');

                        $counterparty->name = $request->get('payer_name_individual');
                        $counterparty->passport_series = $request->get('payer_passport_series');
                        $counterparty->passport_number = $request->get('payer_passport_number');

                        $counterparty->hash_name = md5($request->get('payer_name_individual') . config('app.key'));

                        $counterparty->save();
                    }

                    Auth::user()->counterparties()->syncWithoutDetaching([$counterparty->id]);
                }
            } elseif($payerFormType->slug === 'yuridicheskoe-lico') {
                $order->payer_phone = $request->get('payer_phone_legal');
                $order->payer_addition_info = $request->get('payer_addition_info_legal');

                $order->payer_legal_form = $request->get('payer_legal_form');
                $order->payer_company_name = $request->get('payer_company_name');
                $order->payer_legal_address_city = $request->get('payer_legal_address_city');
                $order->payer_legal_address = $request->get('payer_legal_address');
                $order->payer_contact_person = $request->get('payer_contact_person_legal');
                $order->payer_inn = $request->get('payer_inn');
                $order->payer_kpp = $request->get('payer_kpp');

                if(
                Auth::check()
                ) {
                    $counterparty = Counterparty::where([
                        ['hash_inn', md5($request->get('payer_inn') . config('app.key'))],
                    ])->first();

                    if(!isset($counterparty)) {
                        $counterparty = new Counterparty;
                        $counterparty->type_id = $payerFormType->id;

                        $counterparty->phone = $request->get('payer_phone_legal');
                        $counterparty->contact_person = $request->get('payer_contact_person_legal');
                        $counterparty->addition_info = $request->get('payer_addition_info_legal');

                        $counterparty->legal_form = $request->get('payer_legal_form');
                        $counterparty->company_name = $request->get('payer_company_name');
                        $counterparty->legal_address_city = $request->get('payer_legal_address_city');
                        $counterparty->legal_address = $request->get('payer_legal_address');
                        $counterparty->inn = $request->get('payer_inn');
                        $counterparty->kpp = $request->get('payer_kpp');

                        $counterparty->hash_inn = md5($request->get('payer_inn') . config('app.key'));

                        $counterparty->save();
                    }

                    Auth::user()->counterparties()->syncWithoutDetaching([$counterparty->id]);
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
            $order->payer_legal_address = null;
            $order->payer_contact_person = null;
            $order->payer_inn = null;
            $order->payer_kpp = null;
        }

        $order->discount = $request->get('discount');
        $order->discount_amount = $calculatedData['discount'] ?? 0;
        $order->insurance = $request->get('insurance_amount') ?? 0;
        $order->insurance_amount = $calculatedData['services']['insurance']['total'] ?? 0;
        $order->user_id = Auth::user()->id ?? null;
        $order->enter_id = $_COOKIE['enter_id'];
        $order->payment_type = $paymentType->id;
        $order->status_id = $orderStatus->id;
        $order->payment_status_id = $paymentStatus->id;
        $order->order_creator = $request->get('order-creator');

        $order->order_date = $request->get('order_date') ?? Carbon::now();
        $order->ship_time_from = $request->get('ship_time_from');
        $order->ship_time_to = $request->get('ship_time_to');
        $order->warehouse_schedule = $request->get('warehouse_schedule');
        $order->cargo_comment = $request->get('cargo_comment');

        if($request->get('need-to-take') === "on") {
            $order->take_point = $request->get('ship-from-point') === "on";
            $order->take_in_city = $request->get('need-to-take-type') === "in";
            $order->take_address = $request->get('ship_point'); // Адрес забора

            // Если забор груза за пределами города
            if($request->get('need-to-take-type') === "from") {
                $order->take_city_name = $calculatedData['delivery']['take']['city_name']; // Город забора
                $order->take_distance = $calculatedData['delivery']['take']['distance'] ?? null; // Дистанция от города отправки до адреса забора
            } else { // Если забор в пределах города
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
            $order->delivery_address = $request->get('dest_point'); // Адрес доставки

            // Если доставка за пределами города
            if($request->get('need-to-bring-type') === "from") {
                $order->delivery_city_name = $calculatedData['delivery']['bring']['city_name']; // Город доставки
                $order->delivery_distance = $calculatedData['delivery']['bring']['distance'] ?? null; // Дистанция от города назначения до адреса доставки
            } else { // Если доставка в пределах города
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

        $type = Type::where([
            ['class', 'OrderType'],
            ['slug', $request->get('type')]
        ])->first();

        $order->type_id = $type->id;

        $order->save();

        $order->order_items()->delete();
        $order->order_items()->saveMany($packages);

        $order->order_services()->sync($servicesToSync);

        if($order->status->slug !== "chernovik") {
            if(Auth::check()) {
                EventHelper::createEvent(
                    'Заявка успешно зарегистрирована!',
                    null,
                    1,
                    '/klientam/report/' . $order->id,
                    Auth::id()
                );
            }

            event(new OrderCreated($order));
        }

        PendingFile::whereIn('path', [
            public_path($request->get('take_driving_directions_file')),
            public_path($request->get('delivery_driving_directions_file'))
        ])->delete();

        return $order->status->slug === "chernovik" ?
            redirect(route('calculator-show', [
                'id' => $order->id,
                'type' => $order->type->slug
            ]))->with('message', "Черновик успешно сохранён.") :
            (
                Auth::check() ?
                    redirect(route('orders-list'))->with('message', "Заявка №$order->id успешно сохранена.") :
                    redirect()->back()->with('message', "Заявка №$order->id успешно сохранена.")
            );
    }

    public function saveFile(OrderFileUpload $request)
    {
        $request->validated();

        $uploadFile = $request->file('file');
        $storagePath = Storage::disk('available_public')
            ->put('files/order-files', $uploadFile);

        $pendingFile = new PendingFile();
        $pendingFile->path = public_path($storagePath);
        $pendingFile->save();

        return response()->json([
            'data' => [
                'path' => $storagePath,
                'url' => url($storagePath)
            ]
        ]);
    }

    public function shipmentSearch(Request $request) {
        return View::make('v1.pages.shipment-status.status-page');
    }

    public function shipmentSearchWrapper(Request $request) {
        $messages = [
            'g-recaptcha-response.required'  => 'Подтвердите, что Вы не робот.',
            'query.max'  => 'Длина номера не должна превышать 100 символов',
        ];

        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => ['required', new GoogleReCaptchaV2()],
            'type' => 'string|in:id,cargo_number|max:50',
            'query' => 'string|max:100'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $type = $request->get('type');
        $number = $request->get('query');

        return View::make('v1.pages.shipment-status.status-page-result-wrapper')
            ->with(compact('type', 'number'));
    }

    public function shipmentSearchAjax(Request $request) {
        $data = null;
        $send = [
            'type' => $request->get('type') == 'id' ? 3 : 2,
            'number' => $request->get('number')
        ];

        try {
            $response1c = Api1CHelper::post(
                'cargo_status',
                $send,
                false,
                15
            );

            if(
                $response1c['status'] == 200
                && !empty($response1c['response']['status'])
                && $response1c['response']['status'] === 'success'
                && isset($response1c['response']['data'])
            ) {
                $data = $response1c['response']['data'];
            }
        } catch (\Exception $e) {}

        return view('v1.pages.shipment-status.result')->with(compact('data'))->render();
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

    public function getCargoNumbers(Request $request) {
        $cargoNumbers = DB::table('orders')
            ->where('cargo_number', 'like', "%$request->term%")
            ->select('cargo_number')
            ->limit(5)
            ->get();

        return $cargoNumbers->toArray();
    }
}
