<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 26.04.2019
 * Time: 15:03
 */

namespace App\Http\ViewComposers;

use App\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class EventsCounterViewComposer
{
    public function compose(View $view) {
        $eventCount = 0;

        if($user = Auth::user()) {
            $eventCount = Event::where([
                ['user_id', $user->id],
                ['visible', true]
            ])->count();
        }

        $view->with(compact('eventCount'));
    }
}