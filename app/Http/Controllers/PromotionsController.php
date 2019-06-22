<?php

namespace App\Http\Controllers;

use App\City;
use App\Promotion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class PromotionsController extends Controller
{
    public function showList() {
        $currentCity = null;

        if(Session::has('current_city')) {
            $sessionCity = Session::get('current_city');
            if(isset($sessionCity['id'])) {
                $currentCity = City::where('id', $sessionCity['id'])
                    ->with('closestTerminal')
                    ->first();
            }
        }

        if(!isset($currentCity)) {
            $currentCity = City::where('slug', 'sankt-peterburg')
                ->with('closestTerminal')
                ->firstOrFail();
        }

        $promotions = Promotion::where('end_at', '>', Carbon::now())
            ->whereHas('terminals', function ($terminalsQ) use ($currentCity) {
                return $terminalsQ->where('id', $currentCity->closest_terminal_id);
            })
            ->get();

        return view('v1.pages.promotions.list.list')->with(compact('promotions'));
    }

    public function showSinglePromotion($slug){
        $promotion = Promotion::where([['end_at', '>', Carbon::now()], ['slug', $slug]])->firstOrFail();

        return View::make('v1.pages.promotions.single.single-promotion')->with(compact('promotion'));
    }
}
