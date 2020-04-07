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
                json_encode([
                    "status" => "error",
                    "text" => $validator->errors()->first()
                ], JSON_UNESCAPED_UNICODE),
                400,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']
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
                $address['city'] = $datum['Город'] ?? '';
                $address['address'] = $datum['Представление'] ?? '';
            }

            if($datum['Тип'] === 'Телефон') {
                $phone = $datum['НомерТелефона'] ?? '';
            }
        }

        $legalForms = ['ИП', 'ООО', 'ОДО', 'ОАО', 'ЗАО', 'ПК', 'КФХ', 'ГУП', 'ОО', 'ОД', 'АНО', 'СНТ', 'ДНП', 'ТСЖ'];
        $legalForm = '';

        foreach($legalForms as $form) {
            if(preg_match("/^($form )/i", $data['НаименованиеПолное'])) {
                $legalForm = $form;
                break;
            };
        }

        $name = $data['ЮридическоеФизическоеЛицо'] === 'ФизическоеЛицо' && isset($data['НаименованиеПолное']) ?
            $data['НаименованиеПолное'] :
            null;

        $counterparty = Counterparty::where('code_1c', $data['Ref'])
            ->when(!empty($data['ИНН']), function ($counterpartyQ) use ($data) {
                return $counterpartyQ->orWhere('hash_inn', md5($data['ИНН'] . config('app.key')));
            })
            ->when(!empty($name), function ($counterpartyQ) use ($name) {
                return $counterpartyQ->orWhere('hash_name', md5($name . config('app.key')));
            })
            ->first();

        $fields = [
            'active' => true,
            'code_1c' => $data['Ref'],
            'type_id' => $type->id,
            'legal_form' => $legalForm,
            'company_name' => $data['ЮридическоеФизическоеЛицо'] === 'ЮридическоеЛицо' && isset($data['НаименованиеПолное']) ?
                $data['НаименованиеПолное'] :
                null,
            'legal_address_city' => $address['city'] ?? '',
            'legal_address' => $address['address'] ?? '',
            'inn' => $data['ИНН'] ?? null,
            'kpp' => $data['КПП'] ?? null,
            'phone' => $phone ?? '',
            'name' => $name ?? '',
            'passport_series' => $passportParts[0] ?? null,
            'passport_number' => $passportParts[1] ?? null,
            'addition_info' => $data['Description'] ?? null,
//                'contact_person' => '', // 1c не возвращает
            'hash_name' => $name ? md5($name . config('app.key')) : null,
            'hash_inn' => $data['ИНН'] ? md5($data['ИНН'] . config('app.key')) : null
        ];

        if(isset($counterparty)) {
            $counterparty->update($fields);
        } else {
            Counterparty::create($fields);
        }

        return response(
            json_encode([
                "status" => "success"
            ], JSON_UNESCAPED_UNICODE),
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']
        );
    }
}
