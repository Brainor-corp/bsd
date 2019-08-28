<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CounterpartiesController extends Controller
{
    public function updateCounterparty(Request $request) {
        return $request->all();
    }
}
