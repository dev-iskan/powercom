<?php

Route::namespace('Front')->group(function () {
    Route::get('/main', 'FrontController@main')->name('main'); // copy and paste
    Route::get('/about', 'FrontController@about')->name('about');
    Route::get('/public-offer', 'FrontController@publicOffer')->name('public-offer');
    Route::get('/category/{id}', 'FrontController@category')->name('category');
    Route::get('/product/{id}', 'FrontController@product')->name('product');
    Route::get('/article/{id}', 'FrontController@article')->name('article');
    Route::get('/signup', 'FrontController@signup')->name('signup');
    Route::get('/signin', 'FrontController@signin')->name('signin');
    Route::get('/verify', 'FrontController@verify')->name('verify');
    Route::get('/cart', 'FrontController@cart')->name('cart');
    Route::get('/search', 'FrontController@search')->name('search');
    
    // - steps of process
    // USER Dashboard routes
    // - order
    // - orders
    // - profile
    // - payments
});
