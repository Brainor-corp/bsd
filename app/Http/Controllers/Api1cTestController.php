<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Api1CHelper;
use App\Order;
use Illuminate\Support\Facades\DB;

class Api1cTestController extends Controller
{
    public function newUser() {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'new_user',
            [
                'email' => 'abegunov@mail.ru',
                'tel' => 79817397557
            ]
        );

        dd($response1c);
    }

    public function create_order() {
        $orders = Order::where('sync_need', true)
            ->whereHas('status', function ($statusQ) {
                return $statusQ->where('slug', '<>', 'chernovik');
            })
            ->with(
                'recipient_type',
                'sender_type',
                'payer_form_type',
                'payer',
                'payment',
                'order_services',
                'ship_city',
                'dest_city',
                'status',
                'order_items'
            )->limit(1)->get()->toArray();

        // Преобразуем данные модели в вид, необходимый для отправки в 1с
        $orders = array_map(function ($order) {
            $order['insurance'] = [
                'sum' => $order['insurance'] ?? null,
                'amount' => $order['insurance_amount'] ?? null
            ];

            $order['discount'] = [
                'percent' => $order['discount'] ?? null,
                'amount' => $order['discount_amount'] ?? null
            ];

            $order['take'] = [
                'need' => $order['take_need'] ?? null,
                'in_city' => $order['take_in_city'] ?? null,
                'address' => $order['take_address'] ?? null,
                'distance' => $order['take_distance'] ?? null,
                'point' => $order['take_point'] ?? null,
                'price' => $order['take_price'] ?? null,
                'city_name' => $order['take_city_name'] ?? null,
            ];

            $order['delivery'] = [
                'need' => $order['delivery_need'] ?? null,
                'in_city' => $order['delivery_in_city'] ?? null,
                'address' => $order['delivery_address'] ?? null,
                'distance' => $order['delivery_distance'] ?? null,
                'point' => $order['delivery_point'] ?? null,
                'price' => $order['delivery_price'] ?? null,
                'city_name' => $order['delivery_city_name'] ?? null,
            ];

            $order['ship_city'] = [
                'id' => $order['ship_city']['id'] ?? null,
                'name' => $order['ship_city']['name'] ?? null
            ];

            $order['dest_city'] = [
                'id' => $order['dest_city']['id'] ?? null,
                'name' => $order['dest_city']['name'] ?? null
            ];

            $order['status'] = $order['status']['slug'] ?? null;

            $order['packages'] = array_map(function ($order_item) {
                return [
                    "id" => $order_item['id'] ?? null,
                    "length" => $order_item['length'] ?? null,
                    "width" => $order_item['width'] ?? null,
                    "height" => $order_item['height'] ?? null,
                    "volume" => $order_item['volume'] ?? null,
                    "weight" => $order_item['weight'] ?? null,
                    "quantity" => $order_item['quantity'] ?? null,
                ];
            }, $order['order_items']);

            $order['order_services'] = array_map(function ($order_service) {
                return [
                    'id' => $order_service['id'] ?? null,
                    'name' => $order_service['name'] ?? null,
                    'price' => $order_service['pivot']['price'] ?? null,
                ];
            }, $order['order_services']);

            $order['payment_type'] = $order['payment']['slug'] ?? null;

            $order['sender'] = [
                'type' => $order['sender_type']['slug'] ?? null,
                'legal_form' => $order['sender_legal_form'] ?? null,
                'company_name' => $order['sender_company_name'] ?? null,
                'legal_address' => [
                    'city' => $order['sender_legal_address_city'] ?? null,
                    'street' => $order['sender_legal_address_street'] ?? null,
                    'house' => $order['sender_legal_address_house'] ?? null,
                    'block' => $order['sender_legal_address_block'] ?? null,
                    'building' => $order['sender_legal_address_building'] ?? null,
                    'apartment' => $order['sender_legal_address_apartment'] ?? null,
                ],
                'inn' => $order['sender_inn'] ?? null,
                'kpp' => $order['sender_kpp'] ?? null,
                'name' => $order['sender_name'] ?? null,
                'contact_person' => $order['sender_contact_person'] ?? null,
                'phone' => $order['sender_phone'] ?? null,
                'addition_info' => $order['sender_addition_info'] ?? null,
                'passport_series' => $order['sender_passport_series'] ?? null,
                'passport_number' => $order['sender_passport_number'] ?? null,
            ];

            $order['recipient'] = [
                'type' => $order['recipient_type']['slug'] ?? null,
                'legal_form' => $order['recipient_legal_form'] ?? null,
                'company_name' => $order['recipient_company_name'] ?? null,
                'legal_address' => [
                    'city' => $order['recipient_legal_address_city'] ?? null,
                    'street' => $order['recipient_legal_address_street'] ?? null,
                    'house' => $order['recipient_legal_address_house'] ?? null,
                    'block' => $order['recipient_legal_address_block'] ?? null,
                    'building' => $order['recipient_legal_address_building'] ?? null,
                    'apartment' => $order['recipient_legal_address_apartment'] ?? null,
                ],
                'inn' => $order['recipient_inn'] ?? null,
                'kpp' => $order['recipient_kpp'] ?? null,
                'name' => $order['recipient_name'] ?? null,
                'contact_person' => $order['recipient_contact_person'] ?? null,
                'phone' => $order['recipient_phone'] ?? null,
                'addition_info' => $order['recipient_addition_info'] ?? null,
                'passport_series' => $order['recipient_passport_series'] ?? null,
                'passport_number' => $order['recipient_passport_number'] ?? null,
            ];

            $order['payer'] = [
                'type' => $order['payer']['slug'] ?? null,
                'form_type' => $order['payer_form_type']['slug'] ?? null,
                'legal_form' => $order['payer_legal_form'] ?? null,
                'company_name' => $order['payer_company_name'] ?? null,
                'legal_address' => [
                    'city' => $order['payer_legal_address_city'] ?? null,
                    'street' => $order['payer_legal_address_street'] ?? null,
                    'house' => $order['payer_legal_address_house'] ?? null,
                    'block' => $order['payer_legal_address_block'] ?? null,
                    'building' => $order['payer_legal_address_building'] ?? null,
                    'apartment' => $order['payer_legal_address_apartment'] ?? null,
                ],
                'inn' => $order['payer_inn'] ?? null,
                'kpp' => $order['payer_kpp'] ?? null,
                'name' => $order['payer_name'] ?? null,
                'contact_person' => $order['payer_contact_person'] ?? null,
                'phone' => $order['payer_phone'] ?? null,
                'addition_info' => $order['payer_addition_info'] ?? null,
                'passport_series' => $order['payer_passport_series'] ?? null,
                'passport_number' => $order['payer_passport_number'] ?? null,
            ];

            // Убераем лишние поля
            foreach($this->toUnset as $unsetKey) {
                unset($order[$unsetKey]);
            }

            return $order;
        }, $orders);

        foreach($orders as $order) {
            $response1c = Api1CHelper::post('create_order', $order);
            dd($response1c);
            if(
                $response1c['status'] == 200 &&
                $response1c['status']['status'] === 'success' &&
                !empty($response1c['status']['id'])
            ) {
                DB::table('orders')->where('id', $order['id'])->update([
                    'code_1c' => $response1c['status']['id'],
                    'sync_need' => false
                ]);
            }
        }
    }

    public function documentList() {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'document_list',
            [
                "id" => "e9795c33-97f7-11e8-a972-000d3a28f168",
                "start_date" => "21.01.2019",
                "end_date" => "21.01.2019"
            ]
        );

        dd($response1c);
    }

    public function documentById() {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'document/id',
            [
                "user_id" => "e9795c33-97f7-11e8-a972-000d3a28f168",
                "document_id" => "22345200-abe8-4f60-90c8-0d43c5f6c0f6",
                "type" => 1,
                "empty_fields" => true
            ]
        );

        dd($response1c);
    }

    public function documentByNumber() {
        $response1c = \App\Http\Helpers\Api1CHelper::post(
            'document/number',
            [
                "user_id" => "f008aa7f-29d6-11e9-80c7-000d3a396ad2",
                "number" => "СП00-005673",
                "year" => 2019,
                "type" => 4,
                "empty_fields" => false
            ]
        );

        dd($response1c);
    }
}
