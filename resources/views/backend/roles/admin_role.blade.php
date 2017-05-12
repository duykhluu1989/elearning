@extends('backend.layouts.main')

@section('page_heading', 'Permission')

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
                'data-content' => 'Create Role',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop