<?php

namespace App\Http\Controllers;

use App\City;
use App\Route;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainPageController extends Controller
{
    public function index() {
        $shipCities = City::where('is_ship', true)->get();

        $destinationCities = City::whereIn('id', Route::select(['dest_city_id'])->where('ship_city_id', '53'))
            ->orderBy('name')
            ->get();

        return view('v1.pages.index.index')->with(compact('shipCities','destinationCities'));
    }
}
