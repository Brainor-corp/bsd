<?php

namespace App\Http\Controllers;

use App\Promotion;
use Carbon\Carbon;

class PromotionsController extends Controller
{
    public function showList() {
        $promotions = Promotion::where('end_at', '>', Carbon::now())->get();

        return view('v1.pages.promotions.list.list')->with(compact('promotions'));
    }
}
