@extends('backend.layouts.main')

@section('page_heading', $course->name . ' - Danh Sách Bài Học')

@section('section')

    <?php

    $gridView->setTools([
        function() use($course) {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-plus fa-fw']), [
                'href' => action('Backend\CourseController@createCourseItem', ['id' => $course->id]),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Bài Học Mới',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::a('Quay Lại', [
                'href' => action('Backend\CourseController@adminCourse'),
                'class' => 'btn btn-default',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-trash fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Xóa',
                'value' => action('Backend\CourseController@controlDeleteCourseItem'),
                'style' => 'display: none',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop