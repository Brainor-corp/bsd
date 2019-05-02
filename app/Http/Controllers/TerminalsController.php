<?php

namespace App\Http\Controllers;

use App\City;
use App\Terminal;

class TerminalsController extends Controller
{
    public function showAddresses() {
        $currentCity = null;

        if(isset($_COOKIE['current_city'])) {
            $currentCity = City::where('id', $_COOKIE['current_city'])->first();
        }

        if(!isset($currentCity)) {
            $currentCity = City::where('slug', 'sankt-peterburg')->firstOrFail();
        }

        $terminals = Terminal::where('city_id', $currentCity->id)->get();

        return view('v1.pages.terminals.addresses.addresses')->with(compact('terminals'));
    }
}
