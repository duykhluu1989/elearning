@extends('backend.layouts.main')

@section('page_heading', $case->name . ' - Danh Sách Bước Giải Quyết')

@section('section')

    <?php

    $gridView->setTools([
        function() use($case) {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-plus fa-fw']), [
                'href' => action('Backend\CaseAdviceController@createCaseAdviceStep', ['id' => $case->id]),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Bước Giải Quyết Mới',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::a('Quay Lại', [
                'href' => \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CaseAdviceController@adminCaseAdvice')),
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
                'value' => action('Backend\CaseAdviceController@controlDeleteCaseAdviceStep'),
                'style' => 'display: none',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop