<?php

namespace App\Http\Controllers;

use App\Counterparty;
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

        $counterparties = Counterparty::where([
            ['user_id', Auth::id()],
            ['type_id', $type->id],
            ['active', true],
            [$field, 'like', "%$term%"]
        ])->get();

        return $counterparties->toArray();
    }

    public function showCounterpartyListPage(Request $request) {
        if(Auth::guest()) {
            return [];
        }

        $counterparties = Counterparty::where([
            ['user_id', Auth::id()],
//            ['active', true],
        ])->get();

        return View::make('v1.pages.counterparty.list-page')->with(compact('counterparties'));
    }
}
