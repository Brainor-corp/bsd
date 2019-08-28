<?php

namespace App\Http\Controllers\Api;

use App\Counterparty;
use App\Http\Controllers\Controller;
use App\Rules\INN;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CounterpartiesController extends Controller
{
    public function updateCounterparty(Request $request) {
        $validator = Validator::make($request->all(), [
            "#value.Ref" => "required",
            "#value.ЮридическоеФизическоеЛицо" => "required|in:ЮридическоеЛицо,ФизическоеЛицо",
            "#value.ИНН" => ["nullable", new INN()],
            "#value.ДокументУдостоверяющийЛичность" => "nullable|string|min:10|max:10",
        ]);

        if ($validator->fails()) {
            return response(
                [
                    "status" => "error",
                    "text" => $validator->errors()->first()
                ],
                400
            );
        }

        $data = $request->get('#value');

        $type = Type::where([
            ['class', 'UserType'],
            ['slug', $data['ЮридическоеФизическоеЛицо'] === 'ЮридическоеЛицо' ? 'yuridicheskoe-lico' : 'fizicheskoe-lico']
        ])->first();

        $passportParts = [];
        if(!empty($data['ДокументУдостоверяющийЛичность'])) {
            $passportParts = [
                mb_substr($data['ДокументУдостоверяющийЛичность'], 0, 4),
                mb_substr($data['ДокументУдостоверяющийЛичность'], 4)
            ];
        }

        $address = null;
        $phone = null;

        foreach($data['КонтактнаяИнформация'] as $datum) {
            if($datum['Тип'] === 'Адрес') {
                $address['city'] = $datum['Город'];
                $address['address'] = $datum['Представление'];
            }

            if($datum['Тип'] === 'Телефон') {
                $phone = $datum['НомерТелефона'];
            }
        }

        Counterparty::updateOrCreate(
            ['code_1c' => $data['Ref']],
            [
                'active' => true,
                'type_id' => $type->id,
                'legal_form' => '',
                'company_name' => $data['ЮридическоеФизическоеЛицо'] === 'ЮридическоеЛицо' && isset($data['НаименованиеПолное']) ?
                    $data['НаименованиеПолное'] :
                    null,
                'legal_address_city' => $address['city'] ?? '',
//                'legal_address_street' => '', // todo запросить у 1с
//                'legal_address_house' => '', // todo запросить у 1с
//                'legal_address_block' => '', // todo запросить у 1с
//                'legal_address_building' => '', // todo запросить у 1с
//                'legal_address_apartment' => '', // todo запросить у 1с
                'inn' => $data['ИНН'] ?? null,
                'kpp' => $data['КПП'] ?? null,
                'phone' => $phone ?? '',
                'name' => $data['ЮридическоеФизическоеЛицо'] === 'ФизическоеЛицо' && isset($data['НаименованиеПолное']) ?
                    $data['НаименованиеПолное'] :
                    null,
                'passport_series' => $passportParts[0] ?? null,
                'passport_number' => $passportParts[1] ?? null,
                'addition_info' => $data['Description'] ?? null,
//                'contact_person' => '', // 1c не возвращает
                'hash_name' => $data['ИНН'] ? md5($data['ИНН'] . config('app.key')) : null,
                'hash_inn' => $data['КПП'] ? md5($data['КПП'] . config('app.key')) : null
            ]
        );

        return [
            "status" => "success"
        ];
    }
}
