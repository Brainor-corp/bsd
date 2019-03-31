<?php

namespace App\Http\Controllers;

use App\City;

use Illuminate\Http\Request;

class MainPageController extends Controller
{
    public function index() {

        $shipCities = City::where('is_ship', true)->get();

        return view('v1.pages.index.index')->with(compact('shipCities'));
    }
}
