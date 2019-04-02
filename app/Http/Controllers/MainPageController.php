<?php

namespace App\Http\Controllers;

use App\City;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainPageController extends Controller
{
    public function index() {
        $shipCities = City::where('is_ship', true)->get();

        return view('v1.pages.index.index')->with(compact('shipCities'));
    }
}
