@extends('backend.layouts.main')

@section('page_heading', 'Phương Thức Thanh Toán')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-plus fa-fw']), [
                'href' => action('Backend\PaymentMethodController@createPaymentMethod'),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Phương Thức Thanh Toán Mới',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop