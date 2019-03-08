<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function calculatorShow() {
        return view('v1.pages.calculator.calculator-show.calculator-show');
    }
}
