<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 26.04.2019
 * Time: 15:03
 */

namespace App\Http\ViewComposers;

use App\Promotion;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class PromotionCountViewComposer
{
    public function compose(View $view) {
        $promotionsCount = Promotion::where('end_at', '>', Carbon::now())->count();

        $view->with(compact('promotionsCount'));
    }
}