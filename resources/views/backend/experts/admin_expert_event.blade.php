@extends('backend.layouts.main')

@section('page_heading', 'Lịch Sử Sự Kiện Chuyên Gia - ' . $expert->username)

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a('Quay Lại', [
                'href' => \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUserExpert')),
                'class' => 'btn btn-default',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop