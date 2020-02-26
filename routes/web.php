<?php

Route::namespace('Front')->group(function () {
    Route::get('/main', 'FrontController@main')->name('main'); // copy and paste
    Route::get('/about', 'FrontController@about')->name('about');
    Route::get('/public-offer', 'FrontController@publicOffer')->name('public-offer');
    Route::get('/category/{id}', 'FrontController@category')->name('category');
    Route::get('/product/{id}', 'FrontController@product')->name('product');
    Route::get('/article/{id}', 'FrontController@article')->name('article');
    
    // - steps of process
    // - search result
    // - cart
    
    // - signin
    //  - phone confirmation
    // - signup
    
    // USER Dashboard routes
    // - profile
    // - orders
    // - payments
});
