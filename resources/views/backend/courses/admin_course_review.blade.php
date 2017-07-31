@extends('backend.layouts.main')

@section('page_heading', 'Nhận Xét Khóa Học')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-trash fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Xóa',
                'value' => action('Backend\CourseController@controlDeleteCourseReview'),
                'style' => 'display: none',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-eye fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => \App\Models\CourseReview::STATUS_ACTIVE_LABEL,
                'value' => action('Backend\CourseController@controlChangeStatusCourseReview', ['status' => \App\Models\CourseReview::STATUS_ACTIVE_DB]),
                'style' => 'display: none',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-eye-slash fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => \App\Models\CourseReview::STATUS_INACTIVE_LABEL,
                'value' => action('Backend\CourseController@controlChangeStatusCourseReview', ['status' => \App\Models\CourseReview::STATUS_INACTIVE_DB]),
                'style' => 'display: none',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop