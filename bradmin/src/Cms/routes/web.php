<?php

// Редиректы /////////////////////////////////////////////////////////////////////////////

Route::post('/'.config('bradmin.admin_url').'/cms/{section}', [
    'as'   => 'bradmin.cms.pages.display',
    'uses' => 'Bradmin\Cms\Controllers\CmsController@showRouteRedirect',
]);

Route::post('/'.config('bradmin.admin_url').'/cms/{sectionName}', [
    'as'   => 'bradmin.cms.banks.display-plugin',
    'uses' => 'Bradmin\Cms\Controllers\CmsController@showRouteRedirect',
]);

Route::post('/'.config('bradmin.admin_url').'/cms/{sectionName}/create', [
    'as'   => 'bradmin.cms.banks.create-plugin',
    'uses' => 'Bradmin\Cms\Controllers\CmsController@createRouteRedirect',
]);

Route::post('/'.config('bradmin.admin_url').'/cms/{sectionName}/{id}/edit', [
    'as'   => 'bradmin.cms.banks.edit-plugin',
    'uses' => 'Bradmin\Cms\Controllers\CmsController@editRouteRedirect',
]);

Route::post('/'.config('bradmin.admin_url').'/cms/{sectionName}/{id}/create-action', [
    'as'   => 'bradmin.cms.banks.create-action-plugin',
    'uses' => 'Bradmin\Cms\Controllers\CmsController@createActionRouteRedirect',
]);

Route::post('/'.config('bradmin.admin_url').'/cms/{sectionName}/{id}/edit-action', [
    'as'   => 'bradmin.cms.banks.edit-action-plugin',
    'uses' => 'Bradmin\Cms\Controllers\CmsController@editActionRouteRedirect',
]);

Route::post('/'.config('bradmin.admin_url').'/cms/{sectionName}/{id}/delete', [
    'as'   => 'bradmin.cms.banks.delete-action-plugin',
    'uses' => 'Bradmin\Cms\Controllers\CmsController@deleteActionRouteRedirect',
]);

Route::get(config('bradmin.cms_url_prefix') . '/' .config('bradmin.cms_page_prefix') . '/{slug}' , [
    'as'   => 'bradmin.cms.page.show',
    'uses' => 'Bradmin\Cms\Controllers\CmsController@showPage',
]);

Route::get(config('bradmin.cms_url_prefix') . '/' .config('bradmin.cms_post_prefix') . '/{slug}' , [
    'as'   => 'bradmin.cms.post.show',
    'uses' => 'Bradmin\Cms\Controllers\CmsController@showPost',
]);

Route::post('/'.config('bradmin.admin_url').'/cms/files/upload', [
    'as'   => 'bradmin.cms.file-upload',
    'uses' => 'Bradmin\Cms\Controllers\CmsController@fileUpload',
]);