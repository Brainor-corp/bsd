<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 26.04.2019
 * Time: 15:03
 */

namespace App\Http\ViewComposers;

use App\City;
use App\Promotion;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;

class PromotionCountViewComposer
{
    public function compose(View $view) {
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

        $promotionsCount = Promotion::where('end_at', '>', Carbon::now())
            ->whereHas('terminals', function ($terminalsQ) use ($currentCity) {
                return $terminalsQ->where('id', $currentCity->closest_terminal_id);
            })
            ->count();

        $view->with(compact('promotionsCount'));
    }
}