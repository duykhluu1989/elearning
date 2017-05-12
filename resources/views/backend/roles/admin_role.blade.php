@extends('backend.layouts.main')

@section('page_heading', 'Phân Quyền')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            \App\Libraries\Helpers\Html::a('<i class="fa fa-plus fa-fw"></i>', [
                'href' => action('Backend\RoleController@createRole'),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Vai Trò Mới',
            ]);
        },
        function() {
            \App\Libraries\Helpers\Html::button('<i class="fa fa-trash fa-fw"></i>', [
                'class' => 'btn btn-primary GridViewCheckBoxControl Confirmation',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Xóa',
                'value' => action('Backend\RoleController@controlDeleteRole'),
                'style' => 'display: none',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop