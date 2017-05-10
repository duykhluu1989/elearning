<?php

Route::group(['namespace' => 'Backend'], function() {

    Route::match(['get', 'post'], 'login', 'UserController@login');

    Route::get('/', function() {
        echo 'Logged';
    });

});
