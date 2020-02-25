<?php

Route::namespace('Front')->group(function () {
    Route::get('/main', 'FrontController@main')->name('main'); // copy and paste
    Route::get('/about', 'FrontController@about')->name('about');
    Route::get('/public-offer', 'FrontController@publicOffer')->name('public-offer');
    // - steps of process
    // - search result
    // - news list
    // - news show
    // - category
    // - product
    // - cart
    // - public_offer
    // - contacts

    // - signin
    //  - phone confirmation
    // - signup
    
    // USER Dashboard routes
    // - profile
    // - orders
    // - payments
});
