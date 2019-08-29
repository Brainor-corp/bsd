<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class CounterpartyController extends Controller
{
    public function searchByTerm(Request $request) {
        if(Auth::guest()) {
            return [];
        }

        $type = Type::where([
            ['id', $request->get('type_id')],
            ['class', 'UserType']
        ])->firstOrFail();


        $term = $request->get('term');
        $field = $request->get('field');

        $user = Auth::user();
        $counterparties = $user->counterparties()->where([
            ['type_id', $type->id],
            ['active', true],
        ])->get();

        $counterparties = $counterparties->filter(function ($counterparty) use ($field, $term) {
            return mb_strpos($counterparty->$field, $term) !== FALSE;
        });

        // Расшифровываем поля
        $result = [];
        foreach($counterparties as $counterparty) {
            $decryptedCounterparty = new \stdClass();
            foreach(array_keys($counterparty->getAttributes()) as $attribute) {
                $decryptedCounterparty->$attribute = $counterparty->$attribute;
            }

            $result[] = $decryptedCounterparty;
        }

        return $result;
    }

    public function showCounterpartyListPage(Request $request) {
        if(Auth::guest()) {
            return [];
        }

        $user = Auth::user();
        $counterparties = $user->counterparties()->get();

        return View::make('v1.pages.counterparty.list-page')->with(compact('counterparties'));
    }
}
