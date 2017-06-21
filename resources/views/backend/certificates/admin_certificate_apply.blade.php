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
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-download fa-fw']), [
                'class' => 'btn btn-primary GridViewFilterControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Xuất Dữ Liệu Ra Excel',
                'value' => action('Backend\CertificateController@controlExportExcelCertificateApply'),
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-check fa-fw']), [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Đã Thi',
                'value' => action('Backend\CertificateController@controlSetStatusCertificateApply'),
                'style' => 'display: none',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop