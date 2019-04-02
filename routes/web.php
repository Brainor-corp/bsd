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
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/', 'MainPageController@index')->name('index');
Route::get('/terminals-addresses', 'TerminalsController@showAddresses')->name('terminals-addresses-show');
Route::get('/promotion-list', 'PromotionsController@showList')->name('promotion-list-show');
Route::get('/news-list', 'NewsController@showList')->name('news-list-show');
Route::get('/profile', 'ProfileController@profileData')->name('profile-data-show');
Route::any('/calculator-show', 'CalculatorController@calculatorShow')->name('calculator-show');

Route::get('/home', 'HomeController@index')->name('home');
