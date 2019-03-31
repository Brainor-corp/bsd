<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//CALCULATOR
Route::any('/calculator/get-destination-cities', 'CalculatorController@getDestinationCities')->name('getDestinationCities');
Route::any('/calculator/get-route', 'CalculatorController@getRoute')->name('getRoute');
Route::any('/calculator/get-tariff', 'CalculatorController@getTariff')->name('getTariff');
Route::any('/calculator/get-total-price', 'CalculatorController@getTotalPrice')->name('getTotalPrice');
//END----CALCULATOR

Route::get('/upload-xml-cities', 'UploadXmlController@uploadCities')->name('uploadXmlCities');
Route::get('/upload-xml-thresholds', 'UploadXmlController@uploadThresholds')->name('uploadXmlThresholds');
Route::get('/upload-xml-routes', 'UploadXmlController@uploadRoutes')->name('uploadXmlRoutes');
Route::get('/upload-xml-route-tariff', 'UploadXmlController@uploadRouteTariffs')->name('uploadXmlRouteTariffs');
