<?php

namespace App\Http\Controllers;

use App\Http\Resources\PointSuggestResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointsController extends Controller
{
    public function getPointsByTerm(Request $request) {
        if(empty($request->get('term'))) {
            return [];
        }

        $points = DB::table('points')
            ->whereRaw("LOWER(points.name) like ?", ['%' . mb_strtolower($request->get('term')) . '%'])
            ->join('regions', 'regions.code', '=', 'points.region_code')
            ->join('cities', 'cities.id', '=', 'points.city_id')
            ->select('points.*', 'regions.name as region_name')
            ->where('cities.name', $request->get('city_name'))
            ->limit(10)
            ->get();

        return PointSuggestResource::collection($points);
    }
}
