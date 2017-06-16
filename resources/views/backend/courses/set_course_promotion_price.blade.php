@extends('backend.layouts.main')

@section('page_heading', 'Thiết Lập Giá Khuyến Mãi Khóa Học - ' . $course->name)

@section('section')

    <form action="{{ action('Backend\CourseController@setCoursePromotionPrice', ['id' => $course->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Thiết Lập</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CourseController@adminCourse')) }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <?php
                            $status = old('status', $promotionPrice->status);
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
                            <input type="text" class="form-control DateTimePicker" name="start_time" value="{{ old('start_time', $promotionPrice->start_time) }}" required="required" />
                            @if($errors->has('start_time'))
                                <span class="help-block">{{ $errors->first('start_time') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('end_time') ? ' has-error': '' }}">
                            <label>Thời Gian Kết Thúc <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control DateTimePicker" name="end_time" value="{{ old('end_time', $promotionPrice->end_time) }}" required="required" />
                            @if($errors->has('end_time'))
                                <span class="help-block">{{ $errors->first('end_time') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('price') ? ' has-error': '' }}">
                            <label>Giá Khuyến Mãi <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" name="price" required="required" value="{{ old('price', \App\Libraries\Helpers\Utility::formatNumber($promotionPrice->price)) }}" />
                                <span class="input-group-addon">VND</span>
                            </div>
                            @if($errors->has('price'))
                                <span class="help-block">{{ $errors->first('price') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Thiết Lập</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CourseController@adminCourse')) }}" class="btn btn-default">Quay Lại</a>
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
    </script>
@endpush