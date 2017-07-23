@extends('backend.layouts.main')

@section('page_heading', 'Chi Tiết Đơn Hàng - ' . $order->number)

@section('section')

    <div class="box box-primary">
        <div class="box-header with-border">
            @if(empty($order->cancelled_at))
                @if($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PENDING_DB)

                    <button type="button" class="btn btn-primary" id="SubmitPaymentButton">Xác Nhận Thanh Toán</button>

                @elseif($order->payment_status == \App\Models\Order::PAYMENT_STATUS_COMPLETE_DB)

                    <button type="button" class="btn btn-primary">Hoàn Tiền</button>

                @endif
            @endif

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

    @if(empty($order->cancelled_at))
        @if($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PENDING_DB)

            <div class="modal fade" tabindex="-1" role="dialog" id="SubmitPaymentModal">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Xác Nhận Thanh Toán</h4>
                            </div>
                            <div class="modal-body">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Xác Nhận</button>
                            </div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>

        @elseif($order->payment_status == \App\Models\Order::PAYMENT_STATUS_COMPLETE_DB)

        @endif
    @endif

@stop

@if(empty($order->cancelled_at))
    @if($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PENDING_DB)

        @push('scripts')
            <script type="text/javascript">
                $('#SubmitPaymentButton').click(function() {
                    $('#SubmitPaymentModal').modal('show');
                });
            </script>
        @endpush

    @elseif($order->payment_status == \App\Models\Order::PAYMENT_STATUS_COMPLETE_DB)

    @endif
@endif
