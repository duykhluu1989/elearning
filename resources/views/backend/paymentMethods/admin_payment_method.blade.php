@extends('backend.layouts.main')

@section('page_heading', 'Phương Thức Thanh Toán')

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a(\App\Libraries\Helpers\Html::i('', ['class' => 'fa fa-refresh fa-fw']), [
                'href' => action('Backend\PaymentMethodController@updatePaymentMethod'),
                'class' => 'btn btn-primary',
                'data-container' => 'body',
                'data-toggle' => 'popover',
                'data-placement' => 'top',
                'data-content' => 'Cập Nhật Phương Thức Thanh Toán',
            ]);
        },
    ]);

    $gridView->render();

    ?>

@stop