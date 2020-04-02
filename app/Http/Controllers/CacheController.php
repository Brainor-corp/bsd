<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function clear($key)
    {
        Cache::forget($key);

        return redirect()->back();
    }
}
