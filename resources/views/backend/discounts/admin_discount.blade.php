@extends('backend.layouts.main')

@section('page_heading', 'Mã Giảm Giá')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-plus fa-fw']), [
                'href' => action('Backend\DiscountController@createDiscount'),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Mã Giảm Giá Mới',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop