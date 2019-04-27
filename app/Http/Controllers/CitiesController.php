<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function getCitiesByTerm(Request $request) {
        $term = $request->get('term');
        $limit = $request->get('maxresults');

        return City::where('name', 'like', "%$term%")
            ->limit($limit)
            ->get();
    }

    public function changeCity($city_id) {
        $city = City::where('id', $city_id)
            ->orWhere('slug', 'sankt-peterburg')
            ->select('id', 'slug')
            ->get();

        setcookie(
            "current_city",
            $city->where('id', $city_id)->first()->id ?? $city->where('slug', 'sankt-peterburg')->first()->id ?? '',
            time() + (10 * 365 * 24 * 60 * 60),
            "/"
        );

        return redirect()->back();
    }
}
