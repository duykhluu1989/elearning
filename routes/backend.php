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

        Route::post('articleStatic/autoComplete', 'ArticleController@autoCompleteArticleStatic');

        Route::post('discount/generateCode', 'DiscountController@generateDiscountCode');

        Route::post('user/autoComplete', 'UserController@autoCompleteUser');

        Route::get('district', 'UserController@getListDistrict');

        Route::post('certificate/autoComplete', 'CertificateController@autoCompleteCertificate');

        Route::post('setting/collaborator/value', 'SettingController@getSettingCollaboratorValue');

    });

    Route::group(['middleware' => ['auth', 'access', 'permission']], function() {

        Route::get('order', 'OrderController@adminOrder');

        Route::match(['get', 'post'], 'order/{id}/detail', 'OrderController@detailOrder');

        Route::get('order/{id}/cancel', 'OrderController@cancelOrder');

        Route::post('order/{id}/submitPayment', 'OrderController@submitPaymentOrder');

        Route::get('courseCategory', 'CourseController@adminCategory');

        Route::match(['get', 'post'], 'courseCategory/create', 'CourseController@createCategory');

        Route::match(['get', 'post'], 'courseCategory/{id}/edit', 'CourseController@editCategory');

        Route::get('courseCategory/{id}/delete', 'CourseController@deleteCategory');

        Route::get('courseCategory/controlDelete', 'CourseController@controlDeleteCategory');

        Route::match(['get', 'post'], 'courseCategory/{id}/promotionPrice', 'CourseController@setCategoryPromotionPrice');

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

        Route::get('courseReview', 'CourseController@adminCourseReview');

        Route::get('courseReview/controlDelete', 'CourseController@controlDeleteCourseReview');

        Route::get('courseReview/changeStatus/{status}', 'CourseController@controlChangeStatusCourseReview');

        Route::get('discount', 'DiscountController@adminDiscount');

        Route::match(['get', 'post'], 'discount/create', 'DiscountController@createDiscount');

        Route::match(['get', 'post'], 'discount/{id}/edit', 'DiscountController@editDiscount');

        Route::get('discount/{id}/delete', 'DiscountController@deleteDiscount');

        Route::get('discount/controlDelete', 'DiscountController@controlDeleteDiscount');

        Route::get('newsCategory', 'NewsController@adminCategory');

        Route::match(['get', 'post'], 'newsCategory/create', 'NewsController@createCategory');

        Route::match(['get', 'post'], 'newsCategory/{id}/edit', 'NewsController@editCategory');

        Route::get('newsCategory/{id}/delete', 'NewsController@deleteCategory');

        Route::get('newsCategory/controlDelete', 'NewsController@controlDeleteCategory');

        Route::get('newsArticle', 'NewsController@adminArticle');

        Route::match(['get', 'post'], 'newsArticle/create', 'NewsController@createArticle');

        Route::match(['get', 'post'], 'newsArticle/{id}/edit', 'NewsController@editArticle');

        Route::get('newsArticle/{id}/delete', 'NewsController@deleteArtcle');

        Route::get('newsArticle/controlDelete', 'NewsController@controlDeleteArticle');

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

        Route::match(['get', 'post'], 'articleStatic/create', 'ArticleController@createArticleStatic');

        Route::match(['get', 'post'], 'articleStatic/{id}/edit', 'ArticleController@editArticleStatic');

        Route::get('articleStatic/{id}/delete', 'ArticleController@deleteArticleStatic');

        Route::get('articleStatic/controlDelete', 'ArticleController@controlDeleteArticleStatic');

        Route::get('advice', 'CaseAdviceController@adminCaseAdvice');

        Route::match(['get', 'post'], 'advice/create', 'CaseAdviceController@createCaseAdvice');

        Route::match(['get', 'post'], 'advice/{id}/edit', 'CaseAdviceController@editCaseAdvice');

        Route::get('advice/{id}/delete', 'CaseAdviceController@deleteCaseAdvice');

        Route::get('advice/controlDelete', 'CaseAdviceController@controlDeleteCaseAdvice');

        Route::get('advice/{id}/adviceStep', 'CaseAdviceController@adminCaseAdviceStep');

        Route::match(['get', 'post'], 'advice/{id}/adviceStep/create', 'CaseAdviceController@createCaseAdviceStep');

        Route::match(['get', 'post'], 'adviceStep/{id}/edit', 'CaseAdviceController@editCaseAdviceStep');

        Route::get('adviceStep/{id}/delete', 'CaseAdviceController@deleteCaseAdviceStep');

        Route::get('adviceStep/controlDelete', 'CaseAdviceController@controlDeleteCaseAdviceStep');

        Route::get('certificate', 'CertificateController@adminCertificate');

        Route::match(['get', 'post'], 'certificate/create', 'CertificateController@createCertificate');

        Route::match(['get', 'post'], 'certificate/{id}/edit', 'CertificateController@editCertificate');

        Route::get('certificate/{id}/delete', 'CertificateController@deleteCertificate');

        Route::get('certificate/controlDelete', 'CertificateController@controlDeleteCertificate');

        Route::get('certificateApply', 'CertificateController@adminCertificateApply');

        Route::match(['get', 'post'], 'certificateApply/create', 'CertificateController@createCertificateApply');

        Route::match(['get', 'post'], 'certificateApply/{id}/edit', 'CertificateController@editCertificateApply');

        Route::get('certificateApply/controlSetStatus', 'CertificateController@controlSetStatusCertificateApply');

        Route::get('certificateApply/exportExcel', 'CertificateController@controlExportExcelCertificateApply');

        Route::get('user', 'UserController@adminUser');

        Route::get('userStudent', 'UserController@adminUserStudent');

        Route::get('userCollaborator', 'UserController@adminUserCollaborator');

        Route::get('userTeacher', 'UserController@adminUserTeacher');

        Route::get('userExpert', 'UserController@adminUserExpert');

        Route::match(['get', 'post'], 'user/create', 'UserController@createUser');

        Route::match(['get', 'post'], 'user/{id}/edit', 'UserController@editUser');

        Route::match(['get', 'post'], 'userCollaborator/{id}/edit', 'CollaboratorController@editCollaborator');

        Route::get('userCollaborator/{id}/transaction', 'CollaboratorController@adminCollaboratorTransaction');

        Route::post('userCollaborator/{id}/payment', 'CollaboratorController@paymentCollaborator');

        Route::match(['get', 'post'], 'userTeacher/{id}/edit', 'TeacherController@editTeacher');

        Route::get('userExpert/changeOnline/{online}', 'ExpertController@controlChangeOnlineExpert');

        Route::get('userExpert/{id}/event', 'ExpertController@adminExpertEvent');

        Route::match(['get', 'post'], 'userExpert/{id}/event/create', 'ExpertController@createExpertEvent');

        Route::match(['get', 'post'], 'expertEvent/{id}/edit', 'ExpertController@editExpertEvent');

        Route::get('expertEvent/{id}/delete', 'ExpertController@deleteExpertEvent');

        Route::get('role', 'RoleController@adminRole');

        Route::match(['get', 'post'], 'role/create', 'RoleController@createRole');

        Route::match(['get', 'post'], 'role/{id}/edit', 'RoleController@editRole');

        Route::get('role/{id}/delete', 'RoleController@deleteRole');

        Route::get('role/controlDelete', 'RoleController@controlDeleteRole');

        Route::match(['get', 'post'], 'theme/menu', 'ThemeController@adminMenu');

        Route::post('theme/menu/create', 'ThemeController@createMenu');

        Route::match(['get', 'post'], 'theme/menu/{id}/edit', 'ThemeController@editMenu');

        Route::get('theme/menu/{id}/delete', 'ThemeController@deleteMenu');

        Route::match(['get', 'post'], 'theme/footer', 'ThemeController@adminFooter');

        Route::match(['get', 'post'], 'setting', 'SettingController@adminSetting');

        Route::match(['get', 'post'], 'setting/collaborator', 'SettingController@adminSettingCollaborator');

        Route::match(['get', 'post'], 'setting/social', 'SettingController@adminSettingSocial');

        Route::get('setting/paymentMethod', 'PaymentMethodController@adminPaymentMethod');

        Route::get('setting/paymentMethod/update', 'PaymentMethodController@updatePaymentMethod');

        Route::match(['get', 'post'], 'setting/paymentMethod/{id}/edit', 'PaymentMethodController@editPaymentMethod');

    });

});
