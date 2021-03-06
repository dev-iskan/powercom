<?php

Route::namespace('Front')->group(function () {

    Route::namespace('Auth')->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('register', 'AuthController@showRegistrationForm')->name('show_register');
            Route::post('register', 'AuthController@register')->name('register');

            Route::get('login', 'AuthController@showLoginForm')->name('login');
            Route::post('login', 'AuthController@login')->name('request_login');

            Route::get('request_password_reset', 'AuthController@showRequestPasswordReset')->name('request_password_reset');
            Route::post('request_password_reset', 'AuthController@requestPasswordReset')->name('form_request_password_reset');

            Route::get('password_reset', 'AuthController@showPasswordReset')->name('password_reset');
            Route::post('password_reset', 'AuthController@passwordReset')->name('form_password_reset');
        });

        Route::middleware('auth', 'phone_already_verified')->group(function () {
            Route::get('verify', 'AuthController@showVerifyForm')->name('show_verify');
            Route::get('send_code', 'AuthController@sendCode')->name('send_code');
            Route::post('verify', 'AuthController@verify')->name('verify');
        });

        Route::get('logout', 'AuthController@logout')->name('logout')->middleware('auth');
    });

    Route::namespace('Cart')
        ->middleware('auth', 'phone_verified')
        ->group(function () {
            Route::get('cart', 'CartController@index')->name('cart.index');
            Route::get('cart/store', 'CartController@store')->name('cart.store');
            Route::get('cart/decrement', 'CartController@decrement')->name('cart.decrement');
            Route::get('cart/destroy', 'CartController@destroy')->name('cart.destroy');
        });

    Route::namespace('Profile')
        ->middleware('auth', 'phone_verified')
        ->group(function () {
            Route::get('home', 'ProfileController@index')->name('home');

            Route::post('orders', 'OrderController@store')->name('orders.store');
            Route::get('orders/{id}', 'OrderController@show')->name('order.show');
        });

    Route::namespace('Products')
        ->group(function () {
            Route::get('products', 'ProductController@index')->name('products.index');
            Route::get('products/{id}', 'ProductController@show')->name('product.show');
        });


    Route::get('/', 'FrontController@main')->name('main');
    Route::get('about', 'FrontController@about')->name('about');
    Route::get('public-offer', 'FrontController@publicOffer')->name('public-offer');
    Route::get('article/{id}', 'FrontController@article')->name('article.show');
});
