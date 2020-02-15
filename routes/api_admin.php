<?php

Route::namespace('Auth')
    ->prefix('auth')
    ->group(function () {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout')->middleware('auth:airlock');
        Route::get('me', 'AuthController@me')->middleware('auth:airlock');
    });
