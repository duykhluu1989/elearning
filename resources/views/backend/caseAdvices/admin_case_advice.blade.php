@extends('backend.layouts.main')

@section('page_heading', 'Tư Vấn')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-plus fa-fw']), [
                'href' => action('Backend\CaseAdviceController@createCaseAdvice'),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Tình Huống Mới',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop