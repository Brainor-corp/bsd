<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TerminalsController extends Controller
{
    public function showAddresses() {
        return view('v1.pages.terminals.addresses.addresses');
    }
}
