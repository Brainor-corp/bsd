<?php

namespace App\Http\Controllers;

use App\Promotion;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class PromotionsController extends Controller
{
    public function showList() {
        $promotions = Promotion::where('end_at', '>', Carbon::now())->get();

        return view('v1.pages.promotions.list.list')->with(compact('promotions'));
    }

    public function showSinglePromotion($slug){
        $promotion = Promotion::where([['end_at', '>', Carbon::now()], ['slug', $slug]])->firstOrFail();

        return View::make('v1.pages.promotions.single.single-promotion')->with(compact('promotion'));
    }
}
