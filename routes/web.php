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


// Главная
Route::get('/', 'MainPageController@index')->name('index');

// Список терминалов. Терминалы выводятся согласно текущему заданном городу.
Route::get('/terminals-addresses', 'TerminalsController@showAddresses')->name('terminals-addresses-show');

// Акции
Route::get('/promotion-list', 'PromotionsController@showList')->name('promotion-list-show');
Route::get('/promotion/{id}', 'PromotionsController@showSinglePromotion')->name('promotion-single-show');

// Новости
Route::get('/news-list', 'NewsController@showList')->name('news-list-show');
Route::get('/news/{id}', 'NewsController@showSingleNews')->name('news-single-show');
Route::post('/news-filter', 'NewsController@filterAction')->name('news-filter');

// Документы и сертификаты
Route::get('/o-kompanii/documents-and-certificates', 'DocumentsController@showDocuments')->name('documents-show');

// Отзывы
Route::get('/klientam/reviews', 'ReviewsController@showReviews')->name('reviews');
Route::post('/save-review', 'ReviewsController@saveReview')->name('save-review');

// Первично калькулятор доступен всем. Если в калькулятор передан id,
// калькулятор подцепит по id черновик и проставит все его значения
Route::any('/calculator-show/{id?}', 'CalculatorController@calculatorShow')->name('calculator-show');
Route::any('/calc', 'CalculatorController@calcAjax')->name('home');

// Для сохранения заказа написан middleware 'order.save'.
// Он позволяет сохранять черновики без авторизации,
// но не даёт без авторизации оформить заказ.
Route::post('/order-save', 'OrderController@orderSave')
    ->middleware('order.save')
    ->name('order-save-action');

// Страница со списком отчётов доступна всем пользователям.
// Если пользователь не авторизован, то выводятся отчёты с текущим enter_id.
// Если пользователь авторизован, то выводятся отчёты с текущим enter_id или с текущим user_id.
Route::get('/klientam/report-list', 'ProfileController@showReportListPage')->name('report-list');
Route::post('/search-orders', 'ProfileController@searchOrders')->name('search-orders');
Route::post('/get-order-items', 'ProfileController@actionGetOrderItems')->name('get-order-items');
Route::post('/get-order-search-input', 'ProfileController@actionGetOrderSearchInput')->name('get-order-search-input');
Route::post('/download-reports', 'ProfileController@actionDownloadReports')->name('download-reports');

Route::get('/download-document-request', 'ProfileController@actionDownloadDocumentRequest')->name('download-document-request');
Route::get('/download-document-invoice', 'ProfileController@actionDownloadDocumentInvoice')->name('download-document-invoice');
Route::get('/download-document-transfer', 'ProfileController@actionDownloadDocumentTransfer')->name('download-document-transfer');
Route::get('/download-document-contract', 'ProfileController@actionDownloadDocumentContract')->name('download-document-contract');
Route::get('/download-document-receipt', 'ProfileController@actionDownloadDocumentReceipt')->name('download-document-receipt');

// Партнёры
Route::get('/partners', 'PartnersController@showPartnersPage')->name('partners-page');
Route::post('/partners-action', 'PartnersController@sendPartnersFeedback')->name('partners-page-action');

// Статус груза по номеру заказа
Route::any('/shipment-search', 'OrderController@shipmentSearch')->name('shipment-search');

Route::post('/route-tariffs-options', 'Admin\AdminController@getRouteTariffsOptionsList');
Route::post('/regions-options', 'Admin\AdminController@getRegionsOptionsList');

Route::group(['middleware' => ['auth']], function () {
    // Оформленные заказы могут смотреть только авторизованные пользователи,
    // т.к. оформить заказ можно только будучи авторизованным.
    Route::get('/klientam/report/{id}', 'ProfileController@showReportPage')->name('report-show');

    // Работа с оповещениями доступна только авторизованным пользователям.
    Route::get('/event-list', 'ProfileController@showEventListPage')->name('event-list');
    Route::post('/event-hide', 'ProfileController@actionHideEvent')->name('event-hide');

    // Профиль пользователя
    Route::get('/profile', 'ProfileController@profileData')->name('profile-data-show');
    Route::post('/edit-profile-data', 'ProfileController@edit')->name('edit-profile-data');
});
