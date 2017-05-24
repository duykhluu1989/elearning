<?php

Route::group(['namespace' => 'Backend'], function() {

    Route::group(['middleware' => 'guest'], function() {

        Route::get('login', 'UserController@login');

        Route::post('login', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@login']);

    });

    Route::group(['middleware' => ['auth', 'access']], function() {

        Route::get('logout', 'UserController@logout');

        Route::get('/', 'HomeController@home');

        Route::match(['get', 'post'], 'account/edit', 'UserController@editAccount');

        Route::post('refreshCsrfToken', 'HomeController@refreshCsrfToken');

        Route::post('courseCategory/autoComplete', 'CourseController@autoCompleteCategory');

        Route::post('user/autoComplete', 'UserController@autoCompleteUser');

    });

    Route::group(['middleware' => ['auth', 'access', 'permission']], function() {

        Route::get('courseCategory', 'CourseController@adminCategory');

        Route::match(['get', 'post'], 'courseCategory/create', 'CourseController@createCategory');

        Route::match(['get', 'post'], 'courseCategory/{id}/edit', 'CourseController@editCategory');

        Route::get('courseLevel', 'CourseController@adminLevel');

        Route::match(['get', 'post'], 'courseLevel/create', 'CourseController@createLevel');

        Route::match(['get', 'post'], 'courseLevel/{id}/edit', 'CourseController@editLevel');

        Route::get('course', 'CourseController@adminCourse');

        Route::match(['get', 'post'], 'course/create', 'CourseController@createCourse');

        Route::match(['get', 'post'], 'course/{id}/edit', 'CourseController@editCourse');

        Route::get('widget', 'WidgetController@adminWidget');

        Route::match(['get', 'post'], 'widget/{id}/edit', 'WidgetController@editWidget');

        Route::get('elFinder/popup', 'ElFinderController@popup');

        Route::match(['get', 'post'], 'elFinder/popupConnector', 'ElFinderController@popupConnector');

        Route::get('user', 'UserController@adminUser');

        Route::get('userStudent', 'UserController@adminUserStudent');

        Route::match(['get', 'post'], 'user/create', 'UserController@createUser');

        Route::match(['get', 'post'], 'user/{id}/edit', 'UserController@editUser');

        Route::get('role', 'RoleController@adminRole');

        Route::match(['get', 'post'], 'role/create', 'RoleController@createRole');

        Route::match(['get', 'post'], 'role/{id}/edit', 'RoleController@editRole');

        Route::get('role/{id}/delete', 'RoleController@deleteRole');

        Route::get('role/controlDelete', 'RoleController@controlDeleteRole');

        Route::match(['get', 'post'], 'setting', 'SettingController@adminSetting');

    });

});
