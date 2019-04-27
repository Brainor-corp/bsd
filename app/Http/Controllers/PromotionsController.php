<?php

namespace App\Http\Controllers;

use App\Promotion;

class PromotionsController extends Controller
{
    public function showList() {
        $promotions = Promotion::all();

        return view('v1.pages.promotions.list.list')->with(compact('promotions'));
    }
}
