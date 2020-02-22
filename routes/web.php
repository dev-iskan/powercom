<?php

Route::namespace('Front')->group(function () {
    Route::get('/main', 'FrontController@main'); // copy and paste
    Route::get('/about', 'FrontController@about');
    Route::get('/public-offer', 'FrontController@publicOffer');
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
