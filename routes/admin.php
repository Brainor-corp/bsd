<?php

Route::group(['middleware' => [
//    'auth'
//    , 'admin'
]], function () {
    Route::post(
        '/' . config('zeusAdmin.admin_url') . '/update-cities-closest-terminals',
        'Admin\CitiesClosestTerminalUpdaterController@updateAction'
    )->name('cities-closest-terminal-update-action');
});