@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Phương Thức Thanh Toán - ' . $paymentMethod->name)

@section('section')

    <form action="{{ action('Backend\PaymentMethodController@editPaymentMethod', ['id' => $paymentMethod->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ action('Backend\PaymentMethodController@adminPaymentMethod') }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                            <label>Tên <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $paymentMethod->name) }}" />
                            @if($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Tên EN</label>
                            <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $paymentMethod->name_en) }}" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('order') ? ' has-error': '' }}">
                            <label>Thứ Tự <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="order" required="required" value="{{ old('order', $paymentMethod->order) }}" />
                            @if($errors->has('order'))
                                <span class="help-block">{{ $errors->first('order') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Mã</label>
                            <span class="form-control no-border">{{ $paymentMethod->code }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Loại</label>
                            <span class="form-control no-border">{{ \App\Models\PaymentMethod::getPaymentMethodType($paymentMethod->type) }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <?php
                            $status = old('status', $paymentMethod->status);
                            ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="status" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($status == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <?php

                $payment->renderView($paymentMethod);

                ?>

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ action('Backend\PaymentMethodController@adminPaymentMethod') }}" class="btn btn-default">Quay Lại</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
@endpush