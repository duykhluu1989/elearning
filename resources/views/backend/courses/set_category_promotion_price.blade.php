@extends('backend.layouts.main')

@section('page_heading', 'Thiết Lập Giá Khuyến Mãi Tất Cả Khóa Học Của Chủ Đề - ' . $category->name)

@section('section')

    <form action="{{ action('Backend\CourseController@setCategoryPromotionPrice', ['id' => $category->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Thiết Lập</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CourseController@adminCategory')) }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <?php
                            $status = old('status', \App\Libraries\Helpers\Utility::ACTIVE_DB);
                            ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="status" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($status == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('start_time') ? ' has-error': '' }}">
                            <label>Thời Gian Bắt Đầu <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control DateTimePicker" name="start_time" value="{{ old('start_time') }}" required="required" />
                            @if($errors->has('start_time'))
                                <span class="help-block">{{ $errors->first('start_time') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('end_time') ? ' has-error': '' }}">
                            <label>Thời Gian Kết Thúc <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control DateTimePicker" name="end_time" value="{{ old('end_time') }}" required="required" />
                            @if($errors->has('end_time'))
                                <span class="help-block">{{ $errors->first('end_time') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Loại Giảm Giá</label>
                            <?php
                            $type = old('type', \App\Models\Discount::TYPE_FIX_AMOUNT_DB);
                            ?>
                            <div>
                                @foreach(\App\Models\Discount::getDiscountType() as $value => $label)
                                    @if($type == $value)
                                        <label class="radio-inline">
                                            <input type="radio" name="type" checked="checked" value="{{ $value }}">{{ $label }}
                                        </label>
                                    @else
                                        <label class="radio-inline">
                                            <input type="radio" name="type" value="{{ $value }}">{{ $label }}
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('value') ? ' has-error': '' }}">
                            <label>Giá Trị Giảm Giá <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="ValueInput" name="value" required="required" value="{{ old('value', 1) }}" />
                                <span class="input-group-addon" id="ValueUnit">{{ $type == \App\Models\Discount::TYPE_FIX_AMOUNT_DB ? 'VND' : '%' }}</span>
                            </div>
                            @if($errors->has('value'))
                                <span class="help-block">{{ $errors->first('value') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('value_limit') ? ' has-error': '' }}">
                            <label>Giá Trị Giảm Giá Tối Đa</label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" id="ValueLimitInput" name="value_limit" value="{{ old('value_limit') }}"<?php echo ($type == \App\Models\Discount::TYPE_FIX_AMOUNT_DB ? ' readonly="readonly"' : ''); ?> />
                                <span class="input-group-addon">VND</span>
                            </div>
                            @if($errors->has('value_limit'))
                                <span class="help-block">{{ $errors->first('value_limit') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Thiết Lập</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CourseController@adminCategory')) }}" class="btn btn-default">Quay Lại</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
    <script type="text/javascript">
        $('.DateTimePicker').datetimepicker({
            format: 'Y-m-d H:i:s'
        });

        setValueInputFormatNumber(true);

        $('input[type="radio"][name="type"]').change(function() {
            setValueInputFormatNumber(false);
        });

        function setValueInputFormatNumber(init)
        {
            var valueInputElem = $('#ValueInput');

            if($('input[type="radio"][name="type"]:checked').val() == '{{ \App\Models\Discount::TYPE_FIX_AMOUNT_DB }}')
            {
                $('#ValueUnit').html('VND');
                if(init == false)
                    valueInputElem.val(1);
                valueInputElem.on('keyup', function() {
                    $(this).val(formatNumber($(this).val(), '.'));
                });
                $('#ValueLimitInput').val('').prop('readonly', 'readonly');
            }
            else
            {
                $('#ValueUnit').html('%');
                if(init == false)
                    valueInputElem.val(1);
                valueInputElem.off('keyup');
                $('#ValueLimitInput').removeAttr('readonly');
            }
        }
    </script>
@endpush