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

Route::get('/', 'MainPageController@index')->name('index');
Route::get('/terminals-addresses', 'TerminalsController@showAddresses')->name('terminals-addresses-show');
Route::get('/promotion-list', 'PromotionsController@showList')->name('promotion-list-show');
Route::get('/news-list', 'NewsController@showList')->name('news-list-show');
Route::any('/calculator-show', 'CalculatorController@calculatorShow')->name('calculator-show');
Route::any('/calc', 'CalculatorController@calcAjax')->name('home');


Route::post('/route-tariffs-options', 'Admin\AdminController@getRouteTariffsOptionsList');
Route::post('/regions-options', 'Admin\AdminController@getRegionsOptionsList');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/event-list', 'ProfileController@showEventListPage')->name('event-list');
    Route::post('/event-hide', 'ProfileController@actionHideEvent')->name('event-hide');

    Route::get('/klientam/report-list', 'ProfileController@showReportListPage')->name('report-list');
    Route::get('/klientam/report/{id}', 'ProfileController@showReportListPage')->name('report-show');

    Route::post('/search-orders', 'ProfileController@searchOrders')->name('search-orders');
    Route::post('/get-order-items', 'ProfileController@actionGetOrderItems')->name('get-order-items');
    Route::post('/get-order-search-input', 'ProfileController@actionGetOrderSearchInput')->name('get-order-search-input');

    Route::get('/profile', 'ProfileController@profileData')->name('profile-data-show');
    Route::post('/edit-profile-data', 'ProfileController@edit')->name('edit-profile-data');
});

//Route::get('/test', function (){
//    $name = 'asdsa';
//    $description = 'test';
//    $visible = true;
//    $url = null;
//    $user_id = 10;
//
//    \App\Http\Helpers\EventHelper::createEvent($name, $description, $visible, $url, $user_id);
//});

Route::get('/home', 'HomeController@index')->name('home');
