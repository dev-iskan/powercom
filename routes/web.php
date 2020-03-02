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
    // - steps of process
    // - search result
    // - cart
    
    // USER Dashboard routes
    // - profile
    // - orders
    // - payments
});
