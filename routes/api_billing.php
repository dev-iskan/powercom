<?php

Route::post('payme', 'PaymeController');


Route::post('click/prepare', 'ClickController@prepare');
Route::post('click/complete', 'ClickController@complete');
