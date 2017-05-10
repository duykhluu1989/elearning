<?php

Route::group(['namespace' => 'Backend'], function() {

    Route::get('login', 'UserController@login');

});
