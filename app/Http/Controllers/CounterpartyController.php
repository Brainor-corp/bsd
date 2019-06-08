<?php

namespace App\Http\Controllers;

use App\Counterparty;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
