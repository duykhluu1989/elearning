@extends('backend.layouts.main')

@section('page_heading', 'Lịch Sử Hoa Hồng Cộng Tác Viên - ' . $collaborator->username)

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a('Quay Lại', [
                'href' => \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUserCollaborator')),
                'class' => 'btn btn-default',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop