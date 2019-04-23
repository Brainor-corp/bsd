<?php


namespace App\Http\Helpers;


use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventHelper {
    public static function createEvent($name, $description, $visible = null, $url = null, $user_id = null) {
        $event = new Event();
        $event->user_id = $user_id ?? Auth::user()->id;
        $event->name = $name;
        $event->description = $description;
        $event->visible = $visible ?? true;
        $event->url = $url;

        $event->save();
    }
}