@extends('backend.layouts.main')

@section('page_heading', 'Đăng Kí Cấp Chứng Chỉ')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-plus fa-fw']), [
                'href' => action('Backend\CertificateController@createCertificateApply'),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Đăng Kí Mới',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop