<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/edit-profile-data', 'ProfileController@edit')->name('edit-profile-data');

Route::get('/', 'MainPageController@index')->name('index');
Route::get('/terminals-addresses', 'TerminalsController@showAddresses')->name('terminals-addresses-show');
Route::get('/promotion-list', 'PromotionsController@showList')->name('promotion-list-show');
Route::get('/news-list', 'NewsController@showList')->name('news-list-show');
Route::get('/profile', 'ProfileController@profileData')->name('profile-data-show');
Route::any('/calculator-show', 'CalculatorController@calculatorShow')->name('calculator-show');
Route::any('/calc', 'CalculatorController@calcAjax')->name('home');

Route::post('/order-save', 'OrderController@orderSave')->name('order-save-action');

Route::post('/route-tariffs-options', 'Admin\AdminController@getRouteTariffsOptionsList');
Route::post('/regions-options', 'Admin\AdminController@getRegionsOptionsList');

Route::get('/home', 'HomeController@index')->name('home');
