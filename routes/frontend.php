<?php

Route::group(['namespace' => 'Frontend', 'middleware' => 'locale'], function() {

    Route::group(['middleware' => 'guest'], function() {

        Route::post('login', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@login']);

        Route::post('loginWithFacebook', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@loginWithFacebook']);

        Route::post('register', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@register']);

        Route::post('retrievePassword', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@retrievePassword']);

        Route::get('loginWithToken/{token}', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@loginWithToken']);

    });

    Route::group(['middleware' => ['auth', 'access']], function() {

        Route::get('logout', 'UserController@logout');

    });

    Route::get('/', 'HomeController@home');

    Route::get('language/{locale}', 'HomeController@language');

    Route::post('refreshCsrfToken', 'HomeController@refreshCsrfToken');

    Route::get('course/{id}/{slug}', 'CourseController@detailCourse');

});