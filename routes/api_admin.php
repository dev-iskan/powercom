<?php

Route::namespace('Auth')
    ->prefix('auth')
    ->group(function () {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout')->middleware('auth:airlock');
        Route::get('me', 'AuthController@me')->middleware('auth:airlock');
    });


Route::namespace('Products')
    ->middleware('auth:airlock')
    ->group(function () {
        Route::apiResource('categories', 'CategoryController');
        Route::apiResource('brands', 'BrandController');
        Route::apiResource('products', 'ProductController');
    });
