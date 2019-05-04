<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class EventsController extends Controller
{
    public function showEventListPage() {
        $user = Auth::user();

        $events = Event::where([['user_id', $user->id], ['visible', true]])->get();
        return View::make('v1.pages.profile.profile-inner.event-list-page')->with(compact('events'));
    }

    public function actionHideEvent(Request $request) {
        $event = Event::whereId($request->event_id)->firstOrFail();

        $event->visible = false;
        $event->update();

        return 'ok';
    }
}
