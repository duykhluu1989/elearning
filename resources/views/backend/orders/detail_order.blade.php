@extends('backend.layouts.main')

@section('page_heading', 'Chi Tiết Đơn Hàng - ' . $order->number)

@section('section')

    <div class="box{{ empty($order->cancelled_at) ? ' box-primary' : ' box-danger' }}">
        <div class="box-header with-border">
            @if(empty($order->cancelled_at))
                @if($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PENDING_DB)

                    <button type="button" class="btn btn-primary" id="SubmitPaymentButton">Xác Nhận Thanh Toán</button>

                    <a href="{{ action('Backend\OrderController@cancelOrder', ['id' => $order->id]) }}" class="btn btn-primary pull-right Confirmation">Hủy</a>

                @endif
            @endif

            <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\OrderController@adminOrder')) }}" class="btn btn-default">Quay Lại</a>

            @if(!empty($order->cancelled_at))
                <span class="box-title text-danger pull-right">Đơn Hàng Đã Hủy Vào Lúc {{ $order->cancelled_at }}</span>
            @endif
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
                    else
                        echo \App\Libraries\Helpers\Html::span($status, ['class' => 'label label-danger']);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <p class="page-header">Học Viên</p>
                    {{ $order->user->profile->name }}
                </div>

                @if(!empty($order->discount))
                    <div class="col-sm-4">
                        <p class="page-header">Mã Giám Giá</p>
                        {{ $order->discount->code }}
                    </div>
                @endif

                @if(!empty($order->referral))
                    <div class="col-sm-4">
                        <p class="page-header">Cộng Tác Viên</p>
                        {{ $order->referral->profile->name }}
                    </div>
                @endif
            </div>

            @if(!empty($order->orderAddress))
                <div class="row">
                    <div class="col-sm-4">
                        <p class="page-header">Tên</p>
                        {{ $order->orderAddress->name }}
                    </div>
                    <div class="col-sm-4">
                        <p class="page-header">Email</p>
                        {{ $order->orderAddress->email }}
                    </div>
                    <div class="col-sm-4">
                        <p class="page-header">Số Điện Thoại</p>
                        {{ $order->orderAddress->phone }}
                    </div>
                    <div class="col-sm-4">
                        <p class="page-header">Địa Chỉ</p>
                        {{ $order->orderAddress->address }}
                    </div>
                    <div class="col-sm-4">
                        <p class="page-header">Tỉnh / Thành Phố</p>
                        {{ $order->orderAddress->province }}
                    </div>
                    <div class="col-sm-4">
                        <p class="page-header">Quận / Huyện</p>
                        {{ $order->orderAddress->district }}
                    </div>
                    <div class="col-sm-12">
                        <p class="page-header">Ghi Chú</p>
                        {{ $order->note }}
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-sm-12">
                    <p class="page-header">Khóa Học</p>
                    <div class="table-responsive no-padding">
                        <table class="table table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Giá Tiền</th>
                                <th>Giá Điểm</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->orderItems as $orderItem)
                                <tr>
                                    <td>{{ $orderItem->course->name }}</td>
                                    <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orderItem->price) . ' VND' }}</td>
                                    <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orderItem->point_price) }}</td>
                                </tr>
                            @endforeach

                            @if(!empty($order->discount))
                                <tr>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <th>Giảm Giá</th>
                                    <td>{{ \App\Libraries\Helpers\Utility::formatNumber($order->total_discount_price) . ' VND' }}</td>
                                    <td></td>
                                </tr>
                            @endif

                            <tr>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <th>Tổng</th>
                                <td>{{ \App\Libraries\Helpers\Utility::formatNumber($order->total_price) . ' VND' }}</td>
                                <td>{{ \App\Libraries\Helpers\Utility::formatNumber($order->total_point_price) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(count($order->orderTransactions) > 0)
                <div class="row">
                    <div class="col-sm-12">
                        <p class="page-header">Thanh Toán</p>
                        <div class="table-responsive no-padding">
                            <table class="table table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>Trạng Thái</th>
                                    <th>Tiền</th>
                                    <th>Điểm</th>
                                    <th>Ghi Chú</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->orderTransactions as $orderTransaction)
                                    <tr>
                                        <td>{{ \App\Models\Order::getOrderPaymentStatus($orderTransaction->type) }}</td>
                                        <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orderTransaction->amount) . ' VND' }}</td>
                                        <td>{{ \App\Libraries\Helpers\Utility::formatNumber($orderTransaction->point_amount) }}</td>
                                        <td>{{ $orderTransaction->note }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="box-footer">
            @if(empty($order->cancelled_at))
                @if($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PENDING_DB)

                    <button type="button" class="btn btn-primary" id="SubmitPaymentButton">Xác Nhận Thanh Toán</button>

                    <a href="{{ action('Backend\OrderController@cancelOrder', ['id' => $order->id]) }}" class="btn btn-primary pull-right Confirmation">Hủy</a>

                @endif
            @endif

            <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\OrderController@adminOrder')) }}" class="btn btn-default">Quay Lại</a>
        </div>
    </div>

    @if(empty($order->cancelled_at))
        @if($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PENDING_DB)

            <div class="modal fade" tabindex="-1" role="dialog" id="SubmitPaymentModal">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content box" id="SubmitPaymentModalContent">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Xác Nhận Thanh Toán</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ action('Backend\OrderController@submitPaymentOrder', ['id' => $order->id]) }}" method="post" id="SubmitPaymentModalForm">

                                @include('backend.orders.partials.submit_payment_order_form')

                            </form>
                            {{ csrf_field() }}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-primary" id="SubmitPaymentModalSubmitButton">Xác Nhận</button>
                        </div>
                    </div>
                </div>
            </div>

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

                $('#SubmitPaymentModalSubmitButton').click(function() {
                    var submitPaymentModalFormElem = $('#SubmitPaymentModalForm');

                    if(submitPaymentModalFormElem[0].checkValidity() == false)
                        submitPaymentModalFormElem[0].reportValidity();
                    else
                    {
                        $('#SubmitPaymentModalContent').append('' +
                            '<div class="overlay">' +
                            '<i class="fa fa-refresh fa-spin"></i>' +
                            '</div>' +
                        '');

                        $.ajax({
                            url: submitPaymentModalFormElem.prop('action'),
                            type: 'post',
                            data: '_token=' + $('input[name="_token"]').first().val() + '&' + submitPaymentModalFormElem.serialize(),
                            success: function(result) {
                                if(result)
                                {
                                    if(result == 'Success')
                                        location.reload();
                                    else
                                    {
                                        submitPaymentModalFormElem.html(result);

                                        $('#SubmitPaymentModalContent').find('div[class="overlay"]').first().remove();
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
        @endpush

    @endif
@endif
