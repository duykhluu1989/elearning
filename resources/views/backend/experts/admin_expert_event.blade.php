@extends('backend.layouts.main')

@section('page_heading', 'Lịch Sử Sự Kiện Chuyên Gia - ' . $expert->username)

@section('section')

    <?php

    $gridView->setTools([
        function() use($expert) {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-plus fa-fw']), [
                'href' => action('Backend\ExpertController@createExpertEvent', ['id' => $expert->id]),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Sự Kiện Mới',
            ]);
        },
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