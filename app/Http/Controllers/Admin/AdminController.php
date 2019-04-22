<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\ForwardThreshold;
use App\Http\Controllers\Controller;
use App\InsideForwarding;
use App\OutsideForwarding;
use App\Oversize;
use App\OversizeMarkup;
use App\PerKmTariff;
use App\Point;
use App\Region;
use App\Route;
use App\RouteTariff;
use App\Service;

use App\Threshold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
    public function getRouteTariffsOptionsList(Request $request){
        $thresholds = Threshold::where('rate_id', $request->rate_id)->get();
        return View::make('admin.route-tariffs.optionsList')->with(compact('thresholds'))->render();
    }
}
