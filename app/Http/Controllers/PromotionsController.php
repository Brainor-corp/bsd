<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    public function showList() {
        return view('v1.pages.promotions.list.list');
    }
}
