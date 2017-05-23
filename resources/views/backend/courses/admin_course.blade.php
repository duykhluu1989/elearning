@extends('backend.layouts.main')

@section('page_heading', 'Khóa Học')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-plus fa-fw']), [
                'href' => action('Backend\CourseController@createCourse'),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Khóa Học Mới',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop