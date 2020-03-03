<?php

Route::namespace('Front')->group(function () {

    Route::namespace('Auth')->group(function () {
        Route::get('register', 'AuthController@showRegistrationForm')->name('show_register');
        Route::get('login', 'AuthController@showLoginForm')->name('login')->middleware('guest');
        Route::get('verify', 'AuthController@showVerifyForm')->name('show_verify')->middleware('auth', 'phone_already_verified');

        Route::post('login', 'AuthController@login')->name('request_login')->middleware('guest');
        Route::post('register', 'AuthController@register')->name('register');
        Route::get('send_code', 'AuthController@sendCode')->name('send_code')->middleware('auth', 'phone_already_verified');
        Route::post('verify', 'AuthController@verify')->name('verify')->middleware('auth', 'phone_already_verified');
        Route::get('logout', 'AuthController@logout')->name('logout')->middleware('auth');
    });

    Route::get('/main', 'FrontController@main')->name('main'); // copy and paste
    Route::get('/about', 'FrontController@about')->name('about');
    Route::get('/public-offer', 'FrontController@publicOffer')->name('public-offer');
    Route::get('/category/{id}', 'FrontController@category')->name('category');
    Route::get('/product/{id}', 'FrontController@product')->name('product');
    Route::get('/article/{id}', 'FrontController@article')->name('article');
    Route::get('/cart', 'FrontController@cart')->name('cart');
    Route::get('/search', 'FrontController@search')->name('search');

    // - steps of process
    // USER Dashboard routes
    // - order
    // - orders
    // - profile
    // - payments
});
