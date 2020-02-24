<?php

Route::namespace('Auth')
    ->prefix('auth')
    ->group(function () {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout')->middleware('auth:airlock');
        Route::get('me', 'AuthController@me')->middleware('auth:airlock');
    });

Route::namespace('Settings')
    ->prefix('settings')
    ->middleware('auth:airlock')
    ->group(function () {
        Route::post('get_and_save_token', 'SmsController@getAndSaveToken');
        Route::post('send_sms', 'SmsController@sendSms');
    });

Route::namespace('Users')
    ->middleware('auth:airlock')
    ->group(function () {
        Route::apiResource('users', 'UserController');
        Route::post('users/{user}/toggle_admin', 'UserController@toggleAdmin');
        Route::post('users/{user}/toggle_operator', 'UserController@toggleOperator');
    });

Route::namespace('Clients')
    ->middleware('auth:airlock')
    ->group(function () {
        Route::apiResource('clients', 'ClientController')->except(['destroy']);
    });

Route::namespace('Products')
    ->middleware('auth:airlock')
    ->group(function () {
        Route::apiResource('categories', 'CategoryController');
        Route::apiResource('brands', 'BrandController');
        Route::apiResource('products', 'ProductController');
    });

Route::namespace('Articles')
    ->middleware('auth:airlock')
    ->group(function () {
        Route::apiResource('articles', 'ArticleController');
    });

Route::namespace('Media')
    ->middleware('auth:airlock')
    ->prefix('media')
    ->group(function () {
        Route::get('images/{imageable_type}/{imageable_id}', 'ImageController@index');
        Route::post('images/{imageable_type}/{imageable_id}', 'ImageController@store');
        Route::delete('images/{id}', 'ImageController@destroy');

        Route::get('files', 'FileController@index');
        Route::post('files', 'FileController@store');
        Route::delete('files/{id}', 'FileController@destroy');
    });

Route::namespace('Orders')
    ->middleware('auth:airlock')
    ->group(function () {
        Route::apiResource('orders', 'OrderController')->only(['index', 'store', 'show']);
    });
