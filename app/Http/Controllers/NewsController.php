<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function showList() {
        return view('v1.pages.news.list.list');
    }
}
