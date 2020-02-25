<?php

Route::namespace('Front')->group(function () {
    Route::get('/main', 'FrontController@main')->name('main'); // copy and paste
    Route::get('/about', 'FrontController@about')->name('about');
    Route::get('/public-offer', 'FrontController@publicOffer')->name('public-offer');
    Route::get('/category/{id}', 'FrontController@category')->name('category');
    Route::get('/product/{id}', 'FrontController@product')->name('product');
    // - steps of process
    // - search result
    // - news list
    // - news show
    // - cart
    // - contacts

    // - signin
    //  - phone confirmation
    // - signup
    
    // USER Dashboard routes
    // - profile
    // - orders
    // - payments
});
