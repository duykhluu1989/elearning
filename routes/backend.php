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

        Route::post('course/autoComplete', 'CourseController@autoCompleteCourse');

        Route::post('courseTag/autoComplete', 'CourseController@autoCompleteTag');

        Route::post('discount/generateCode', 'DiscountController@generateDiscountCode');

        Route::post('user/autoComplete', 'UserController@autoCompleteUser');

        Route::post('setting/collaborator/value', 'SettingController@getSettingCollaboratorValue');

    });

    Route::group(['middleware' => ['auth', 'access', 'permission']], function() {

        Route::get('courseCategory', 'CourseController@adminCategory');

        Route::match(['get', 'post'], 'courseCategory/create', 'CourseController@createCategory');

        Route::match(['get', 'post'], 'courseCategory/{id}/edit', 'CourseController@editCategory');

        Route::get('courseCategory/{id}/delete', 'CourseController@deleteCategory');

        Route::get('courseCategory/controlDelete', 'CourseController@controlDeleteCategory');

        Route::get('courseLevel', 'CourseController@adminLevel');

        Route::match(['get', 'post'], 'courseLevel/create', 'CourseController@createLevel');

        Route::match(['get', 'post'], 'courseLevel/{id}/edit', 'CourseController@editLevel');

        Route::get('courseLevel/{id}/delete', 'CourseController@deleteLevel');

        Route::get('courseLevel/controlDelete', 'CourseController@controlDeleteLevel');

        Route::get('course', 'CourseController@adminCourse');

        Route::match(['get', 'post'], 'course/create', 'CourseController@createCourse');

        Route::match(['get', 'post'], 'course/{id}/edit', 'CourseController@editCourse');

        Route::get('course/{id}/delete', 'CourseController@deleteCourse');

        Route::get('course/controlDelete', 'CourseController@controlDeleteCourse');

        Route::match(['get', 'post'], 'course/{id}/promotionPrice', 'CourseController@setCoursePromotionPrice');

        Route::get('course/{id}/courseItem', 'CourseController@adminCourseItem');

        Route::match(['get', 'post'], 'course/{id}/courseItem/create', 'CourseController@createCourseItem');

        Route::match(['get', 'post'], 'courseItem/{id}/edit', 'CourseController@editCourseItem');

        Route::get('courseItem/{id}/delete', 'CourseController@deleteCourseItem');

        Route::get('courseItem/controlDelete', 'CourseController@controlDeleteCourseItem');

        Route::get('courseTag', 'CourseController@adminTag');

        Route::match(['get', 'post'], 'courseTag/create', 'CourseController@createTag');

        Route::match(['get', 'post'], 'courseTag/{id}/edit', 'CourseController@editTag');

        Route::get('courseTag/{id}/delete', 'CourseController@deleteTag');

        Route::get('courseTag/controlDelete', 'CourseController@controlDeleteTag');

        Route::get('discount', 'DiscountController@adminDiscount');

        Route::match(['get', 'post'], 'discount/create', 'DiscountController@createDiscount');

        Route::match(['get', 'post'], 'discount/{id}/edit', 'DiscountController@editDiscount');

        Route::get('discount/{id}/delete', 'DiscountController@deleteDiscount');

        Route::get('discount/controlDelete', 'DiscountController@controlDeleteDiscount');

        Route::get('widget', 'WidgetController@adminWidget');

        Route::match(['get', 'post'], 'widget/{id}/edit', 'WidgetController@editWidget');

        Route::get('elFinder/popup', 'ElFinderController@popup');

        Route::match(['get', 'post'], 'elFinder/popupConnector', 'ElFinderController@popupConnector');

        Route::get('elFinder/tinymce', 'ElFinderController@tinymce');

        Route::get('article', 'ArticleController@adminArticle');

        Route::match(['get', 'post'], 'article/create', 'ArticleController@createArticle');

        Route::match(['get', 'post'], 'article/{id}/edit', 'ArticleController@editArticle');

        Route::get('article/{id}/delete', 'ArticleController@deleteArticle');

        Route::get('article/controlDelete', 'ArticleController@controlDeleteArticle');

        Route::get('articleStatic', 'ArticleController@adminArticleStatic');

        Route::match(['get', 'post'], 'articleStatic/{id}/edit', 'ArticleController@editArticleStatic');

        Route::get('user', 'UserController@adminUser');

        Route::get('userStudent', 'UserController@adminUserStudent');

        Route::get('userCollaborator', 'UserController@adminUserCollaborator');

        Route::match(['get', 'post'], 'user/create', 'UserController@createUser');

        Route::match(['get', 'post'], 'user/{id}/edit', 'UserController@editUser');

        Route::match(['get', 'post'], 'userCollaborator/{id}/edit', 'CollaboratorController@editCollaborator');

        Route::get('role', 'RoleController@adminRole');

        Route::match(['get', 'post'], 'role/create', 'RoleController@createRole');

        Route::match(['get', 'post'], 'role/{id}/edit', 'RoleController@editRole');

        Route::get('role/{id}/delete', 'RoleController@deleteRole');

        Route::get('role/controlDelete', 'RoleController@controlDeleteRole');

        Route::match(['get', 'post'], 'setting', 'SettingController@adminSetting');

        Route::match(['get', 'post'], 'setting/collaborator', 'SettingController@adminSettingCollaborator');

        Route::get('setting/paymentMethod', 'PaymentMethodController@adminPaymentMethod');

        Route::match(['get', 'post'], 'setting/paymentMethod/create', 'PaymentMethodController@createPaymentMethod');

        Route::match(['get', 'post'], 'setting/paymentMethod/{id}/edit', 'PaymentMethodController@editPaymentMethod');

    });

});
