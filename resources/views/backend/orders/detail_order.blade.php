@extends('backend.layouts.main')

@section('page_heading', 'Chi Tiết Đơn Hàng - ' . $order->number)

@section('section')

    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\OrderController@adminOrder')) }}" class="btn btn-default">Quay Lại</a>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-4">
                    <p class="page-header">Thời Gian Đặt Đơn Hàng</p>
                    {{ $order->created_at }}
                </div>
                <div class="col-sm-4">
                    <p class="page-header">Phương Thức Thanh Toán</p>
                    {{ $order->paymentMethod->name }}
                </div>
                <div class="col-sm-4">
                    <p class="page-header">Trạng Thái Thanh toán</p>
                    <?php
                    $status = \App\Models\Order::getOrderPaymentStatus($order->payment_status);
                    if($order->payment_status == \App\Models\Order::PAYMENT_STATUS_COMPLETE_DB)
                        echo \App\Libraries\Helpers\Html::span($status, ['class' => 'label label-success']);
                    else if($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PENDING_DB)
                        echo \App\Libraries\Helpers\Html::span($status, ['class' => 'label label-danger']);
                    else
                        echo \App\Libraries\Helpers\Html::span($status, ['class' => 'label label-warning']);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="page-header">Khóa Học</p>
                    <div class="table-responsive no-padding">
                        <table class="table table-striped table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Giá Tiền</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->orderItems as $orderItem)
                                <tr>
                                    <td>{{ $orderItem->course->name }}</td>
                                    <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orderItem->price) . ' VND' }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <th>Tổng Tiền</th>
                                <td>{{ \App\Libraries\Helpers\Utility::formatNumber($order->total_price) . ' VND' }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\OrderController@adminOrder')) }}" class="btn btn-default">Quay Lại</a>
        </div>
    </div>

@stop