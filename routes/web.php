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



Route::group(['middleware' => ['geoIpCheck']], function () {
    Auth::routes();
    Route::post('/password-method-redirect', 'Auth\ForgotPasswordController@resetMethodRedirect')->name('password.method-redirect');
    Route::get('/restore-phone-confirm', 'Auth\ForgotPasswordController@restorePhoneConfirmShow')->name('password.restore-phone-confirm');
    Route::post('/restore-phone-confirm-action', 'Auth\ForgotPasswordController@restorePhoneConfirmAction')->name('password.restore-phone-confirm-action');
    Route::get('/reset-by-phone/{token}', 'Auth\ForgotPasswordController@resetByPhonePage')->name('password.reset-by-phone');
    Route::post('/reset-by-phone-action', 'Auth\ForgotPasswordController@resetByPhoneAction')->name('password.reset-by-phone-action');
    Route::get('/resend-sms-code', 'Auth\ForgotPasswordController@resendSmsCode')->name('password.resend-sms-code');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/turbo/rss/pages', 'TurboController@rssPages')->name('turbo-rss-pages');
    Route::get('/turbo/rss/news', 'TurboController@rssNews')->name('turbo-rss-news');

    // Платежи
    Route::get('/make-payment/{order_id}', 'PaymentController@makePayment')->name('make-payment');

    Route::group(['middleware' => ['password_reset']], function () {
        // Главная
        Route::get('/', 'MainPageController@index')->name('index');
    });

    // Список терминалов. Терминалы выводятся согласно текущему заданном городу.
    Route::get('/terminals-addresses/{city?}', 'TerminalsController@showAddresses')->name('terminals-addresses-show');

    // Акции
    Route::get('/promotions', 'PromotionsController@showList')->name('promotion-list-show');
    Route::get('/promotions/{slug}', 'PromotionsController@showSinglePromotion')->name('promotion-single-show');

    // Новости
    Route::get('/news', 'NewsController@showList')->name('news-list-show');
    Route::get('/news/{slug}', 'NewsController@showSingleNews')->name('news-single-show');
    Route::post('/news-filter', 'NewsController@filterAction')->name('news-filter');

    // Отзывы
    Route::get('/klientam/reviews', 'ReviewsController@showReviews')->name('reviews');
    Route::post('/save-review', 'ReviewsController@saveReview')->name('save-review');

    //Цены
    Route::get('/prices', 'PricesController@pricesPage')->name('pricesPage');


    // Первично калькулятор доступен всем. Если в калькулятор передан id,
    // калькулятор подцепит по id черновик и проставит все его значения
    Route::any('/calculator-show/{id?}', 'CalculatorController@calculatorShow')->name('calculator-show');
    Route::any('/calc', 'CalculatorController@calcAjax')->name('home');

    Route::post('/order-file-upload', 'OrderController@saveFile')->name('order-file-upload');

    Route::group(['middleware' => 'sms-confirm'], function () {
        Route::post('/order-save', 'OrderController@orderSave')
            ->middleware('order.save')
            ->name('order-save-action');

        Route::get('/cabinet/counterparty-list', 'CounterpartyController@showCounterpartyListPage')->name('counterparty-list');
    });

    // Партнёры
    Route::get('/partners', 'PartnersController@showPartnersPage')->name('partners-page');
    Route::post('/partners-action', 'PartnersController@sendPartnersFeedback')->name('partners-page-action');

    // Статус груза по номеру заказа
    Route::any('/shipment-search', 'OrderController@shipmentSearch')->name('shipment-search');

    Route::group(['middleware' => ['auth']], function () {
        Route::group(['middleware' => ['admin']], function () {
            Route::post('/admin/orders/resend/admin-email', 'Admin\OrdersController@resendAdminEmail')->name('admin-resend-admin-email');
            Route::post('/admin/orders/resend/order-to-1c', 'Admin\OrdersController@resendTo1c')->name('admin-resend-order-to-1c');
            Route::post('/admin/orders/resend/order-to-email', 'Admin\OrdersController@resendToEmail')->name('admin-resend-order-to-email');

            Route::get('/find-doubles', function () {
                $forwardingReceipts = \App\Order::all()->pluck('code_1c')->toArray();

                $result = [];
                foreach($forwardingReceipts as $forwardingReceipt) {
                    $result[$forwardingReceipt] = \App\Order::where('code_1c', $forwardingReceipt)->count();
                }

                dd($result);
            });

            Route::get('/rename-forward-thresholds', function () {
                $forwardThresholds = \App\ForwardThreshold::get();

                foreach($forwardThresholds as $forwardThreshold) {
                    $forwardThreshold->name_params = $forwardThreshold->name;
                    $forwardThreshold->save();
                }
            });
        });

        // Подтверждение регистрации по СМС
        Route::post('/phone-confirmation', 'ProfileController@phoneConfirm')->name('phone-confirmation');
        Route::any('/resend-phone-confirm-code', 'ProfileController@resendPhoneConfirmCode')->name('resend-phone-confirm-code');

        // Профиль пользователя
        Route::get('/profile', 'ProfileController@profileData')->name('profile-data-show');
        Route::post('/edit-profile-data', 'ProfileController@edit')->name('edit-profile-data');

        Route::get('/profile/balance', 'ProfileController@balancePageShow')->name('profile-balance-show');
        Route::post('/profile/balance/get', 'ProfileController@balanceGet')->name('profile-balance-get');

        Route::get('/profile/contract', 'ProfileController@contractPageShow')->name('profile-contract-show');
        Route::post('/profile/contract/download', 'ProfileController@contractDownload')->name('profile-contract-download');

        // Список заявок
        Route::get('/klientam/report-list', 'ReportsController@showReportListPage')->name('report-list');
        Route::get('/cabinet/orders', 'ReportsController@showReportListPage')->name('orders-list');
        Route::get('/klientam/report/{id}', 'ReportsController@showReportPage')->name('report-show');
        Route::post('/download-reports', 'ReportsController@actionDownloadReports')->name('download-reports');
        Route::post('/search-orders', 'ReportsController@searchOrders')->name('search-orders');
        Route::post('/get-order-items', 'OrderController@actionGetOrderItems')->name('get-order-items');
        Route::post('/get-order-search-input', 'OrderController@actionGetOrderSearchInput')->name('get-order-search-input');
        Route::post('/get-download-documents-modal', 'ReportsController@getDownloadDocumentsModal')->name('get-download-documents-modal');
        Route::get('/download-document/{document_id_1c}/{document_type_id_1c}', 'ReportsController@downloadOrderDocument')->name('download-document');

        Route::group(['middleware' => ['sms-confirm']], function () {
            // Работа с оповещениями доступна только авторизованным пользователям.
            Route::get('/event-list', 'EventsController@showEventListPage')->name('event-list');
            Route::post('/event-hide', 'EventsController@actionHideEvent')->name('event-hide');
        });
    });
});


//Route::get('/1c/test/new-user', 'Api1cTestController@newUser');
//Route::get('/1c/test/create-order', 'Api1cTestController@createOrder');
//Route::get('/1c/test/document-list', 'Api1cTestController@documentList');
//Route::get('/1c/test/document/id', 'Api1cTestController@documentById');
//Route::get('/1c/test/print_form', 'Api1cTestController@printForm');
//Route::get('/1c/test/document/number', 'Api1cTestController@documentByNumber');
//Route::get('/1c/test/orders', 'Api1cTestController@orders');
//Route::get('/1c/test/contract', 'Api1cTestController@contract');
//Route::get('/1c/test/discount', 'Api1cTestController@discount');
//Route::get('/1c/test/new-client', 'Api1cTestController@newClient');
//Route::get('/1c/test/client-by-id', 'Api1cTestController@clientById');
//Route::get('/1c/test/update-order-payment-status', 'Api1cTestController@updateOrderPaymentStatus');
//
//Route::get('/auth-user/{id}', function ($id) {
//    $user = \App\User::where('id', $id)->firstOrfail();
//    \Illuminate\Support\Facades\Auth::login($user);
//
//    return redirect('/');
//});
