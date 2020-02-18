<?php

Route::namespace('Auth')
    ->prefix('auth')
    ->group(function () {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout')->middleware('auth:airlock');
        Route::get('me', 'AuthController@me')->middleware('auth:airlock');
    });

Route::namespace('Users')
    ->middleware('auth:airlock')
    ->group(function () {
        Route::apiResource('users', 'UserController');
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
