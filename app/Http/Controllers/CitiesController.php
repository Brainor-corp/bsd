<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function getCitiesByTerm(Request $request) {
        $term = $request->get('term');
        $limit = $request->get('maxresults');

        return City::where([
            ['name', 'like', "%$term%"],
            ['is_filial', true]
        ])
            ->limit($limit)
            ->get();
    }

    public function changeCity($city_id, Request $request) {
        $city = City::where('id', $city_id)
            ->select('id', 'slug', 'name')
            ->first();

        if(!isset($city)) {
            $city = City::where('id', $city_id)
                ->where('slug', 'sankt-peterburg')
                ->firstOrFail();
        }

        $cookieValue = [
            'name'          =>   $city->name ?? '',
            'id'            =>   $city->id ?? '',
            'confirmed'     =>   true
        ];
        setcookie(
            "current_city",
            serialize($cookieValue),
            time() + (10 * 365 * 24 * 60 * 60),
            "/"
        );
        $request->session()->put('current_city', $cookieValue);

        return redirect()->back();
    }
}
