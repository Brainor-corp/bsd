<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profileData() {
        return view('v1.pages.profile.profile-data.profile-data');
    }
}
