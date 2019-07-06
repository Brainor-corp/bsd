<?php

namespace App\Http\Controllers;

use App\City;
use App\Terminal;
use Illuminate\Http\Request;

class TerminalsController extends Controller
{
    public function showAddresses(Request $request) {
        $currentCity = null;

        if($request->session()->has('current_city')) {
            $sessionCity = $request->session()->get('current_city');
            if(isset($sessionCity['id']) && is_numeric($sessionCity['id'])) {
                $currentCity = City::where('id', $sessionCity['id'])->with('requisites.requisiteParts')->first();
            }
        }

        if(!isset($currentCity)) {
            $currentCity = City::where('slug', 'sankt-peterburg')->with('requisites.requisiteParts')->firstOrFail();
        }

        $terminals = Terminal::where('city_id', $currentCity->id)->get();

        return view('v1.pages.terminals.addresses.addresses')
            ->with(compact('terminals', 'currentCity'));
    }
}
