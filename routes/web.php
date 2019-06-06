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



Route::group(['middleware' => ['password_reset']], function () {

    Auth::routes();
    Route::post('/password-method-redirect', 'Auth\ForgotPasswordController@resetMethodRedirect')->name('password.method-redirect');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    // Главная
    Route::get('/', 'MainPageController@index')->name('index');

    // Список терминалов. Терминалы выводятся согласно текущему заданном городу.
    Route::get('/terminals-addresses', 'TerminalsController@showAddresses')->name('terminals-addresses-show');

    // Акции
    Route::get('/promotions', 'PromotionsController@showList')->name('promotion-list-show');
    Route::get('/promotions/{slug}', 'PromotionsController@showSinglePromotion')->name('promotion-single-show');

    // Новости
    Route::get('/news', 'NewsController@showList')->name('news-list-show');
    Route::get('/news/{slug}', 'NewsController@showSingleNews')->name('news-single-show');
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

    Route::group(['middleware' => 'sms-confirm'], function () {
        // Для сохранения заказа написан middleware 'order.save'.
        // Он позволяет сохранять черновики без авторизации,
        // но не даёт без авторизации оформить заказ.
        Route::post('/order-save', 'OrderController@orderSave')
            ->middleware('order.save')
            ->name('order-save-action');

        // Страница со списком отчётов доступна всем пользователям.
        // Если пользователь не авторизован, то выводятся отчёты с текущим enter_id.
        // Если пользователь авторизован, то выводятся отчёты с текущим enter_id или с текущим user_id.
        Route::get('/klientam/report-list', 'ReportsController@showReportListPage')->name('report-list');
        Route::get('/klientam/orders', 'ReportsController@showReportListPage')->name('orders-list');
        Route::post('/download-reports', 'ReportsController@actionDownloadReports')->name('download-reports');
        Route::post('/search-orders', 'OrderController@searchOrders')->name('search-orders');
        Route::post('/get-order-items', 'OrderController@actionGetOrderItems')->name('get-order-items');
        Route::post('/get-order-search-input', 'OrderController@actionGetOrderSearchInput')->name('get-order-search-input');
    });

    Route::get('/download-document-request', 'ReportsController@actionDownloadDocumentRequest')->name('download-document-request');
    Route::get('/download-document-invoice', 'ReportsController@actionDownloadDocumentInvoice')->name('download-document-invoice');
    Route::get('/download-document-transfer', 'ReportsController@actionDownloadDocumentTransfer')->name('download-document-transfer');
    Route::get('/download-document-contract', 'ReportsController@actionDownloadDocumentContract')->name('download-document-contract');
    Route::get('/download-document-receipt', 'ReportsController@actionDownloadDocumentReceipt')->name('download-document-receipt');

    // Партнёры
    Route::get('/partners', 'PartnersController@showPartnersPage')->name('partners-page');
    Route::post('/partners-action', 'PartnersController@sendPartnersFeedback')->name('partners-page-action');

    // Статус груза по номеру заказа
    Route::any('/shipment-search', 'OrderController@shipmentSearch')->name('shipment-search');

    Route::group(['middleware' => ['auth']], function () {
        // Подтверждение регистрации по СМС
        Route::post('/phone-confirmation', 'ProfileController@phoneConfirm')->name('phone-confirmation');
        Route::any('/resend-phone-confirm-code', 'ProfileController@resendPhoneConfirmCode')->name('resend-phone-confirm-code');

        // Профиль пользователя
        Route::get('/profile', 'ProfileController@profileData')->name('profile-data-show');
        Route::post('/edit-profile-data', 'ProfileController@edit')->name('edit-profile-data');

        Route::group(['middleware' => ['sms-confirm']], function () {
            // Оформленные заказы могут смотреть только авторизованные пользователи,
            // т.к. оформить заказ можно только будучи авторизованным.
            Route::get('/klientam/report/{id}', 'ReportsController@showReportPage')->name('report-show');

            // Работа с оповещениями доступна только авторизованным пользователям.
            Route::get('/event-list', 'EventsController@showEventListPage')->name('event-list');
            Route::post('/event-hide', 'EventsController@actionHideEvent')->name('event-hide');
        });
    });
});

//Route::group(['middleware' => ['auth']], function () {
//    // Профиль пользователя
//    Route::get('/profile', 'ProfileController@profileData')->name('profile-data-show');
//    Route::post('/edit-profile-data', 'ProfileController@edit')->name('edit-profile-data');
//
//
//    //Tests
//    Route::get('/test-1', 'TestController@test1')->name('test1');
//});
