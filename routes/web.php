<?php

Route::namespace('Front')->group(function () {

    Route::namespace('Auth')->group(function () {
        Route::get('register', 'AuthController@showRegistrationForm')->name('show_register')->middleware('guest');
        Route::get('login', 'AuthController@showLoginForm')->name('login')->middleware('guest');
        Route::get('verify', 'AuthController@showVerifyForm')->name('show_verify')->middleware('auth', 'phone_already_verified');

        Route::post('login', 'AuthController@login')->name('request_login')->middleware('guest');
        Route::post('register', 'AuthController@register')->name('register')->middleware('guest');
        Route::get('send_code', 'AuthController@sendCode')->name('send_code')->middleware('auth', 'phone_already_verified');
        Route::post('verify', 'AuthController@verify')->name('verify')->middleware('auth', 'phone_already_verified');
        Route::get('logout', 'AuthController@logout')->name('logout')->middleware('auth');
    });

    Route::namespace('Cart')->group(function () {
        Route::get('cart', 'CartController@index')->name('cart.index');
        Route::get('cart/store', 'CartController@store')->name('cart.store');
        Route::get('cart/decrement', 'CartController@decrement')->name('cart.decrement');
        Route::get('cart/destroy', 'CartController@destroy')->name('cart.destroy');
    });

    Route::namespace('Orders')->group(function () {
        Route::post('orders', 'OrderController@store')->name('orders.store');
    });

    Route::get('/', 'FrontController@main')->name('main'); // copy and paste
    Route::get('/about', 'FrontController@about')->name('about');
    Route::get('/public-offer', 'FrontController@publicOffer')->name('public-offer');
    Route::get('/category/{id}', 'FrontController@category')->name('category');
    Route::get('/product/{id}', 'FrontController@product')->name('product');
    Route::get('/article/{id}', 'FrontController@article')->name('article');
    Route::get('/search', 'FrontController@search')->name('search');
});
