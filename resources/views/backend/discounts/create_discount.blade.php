@extends('backend.layouts.main')

@section('page_heading', 'Mã Giảm Giá Mới')

@section('section')

    <form action="{{ action('Backend\DiscountController@createDiscount') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Tạo Mới</button>
                <a href="{{ action('Backend\DiscountController@adminDiscount') }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group{{ $errors->has('code') ? ' has-error': '' }}">
                            <label>Mã <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" id="CodeInput" name="code" required="required" value="{{ old('code', $discount->code) }}" />
                            @if($errors->has('code'))
                                <span class="help-block">{{ $errors->first('code') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tạo Mã Tự Động <i>(nhập số kí tự của mã: 1 - 255)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="GenerateCodeInput" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" id="GenerateCodeButton">Tạo Mã</button>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('start_time') ? ' has-error': '' }}">
                            <label>Thời Gian Bắt Đầu</label>
                            <input type="text" class="form-control DateTimePicker" name="start_time" value="{{ old('start_time', $discount->start_time) }}" />
                            @if($errors->has('start_time'))
                                <span class="help-block">{{ $errors->first('start_time') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('end_time') ? ' has-error': '' }}">
                            <label>Thời Gian Kết Thúc</label>
                            <input type="text" class="form-control DateTimePicker" name="end_time" value="{{ old('end_time', $discount->end_time) }}" />
                            @if($errors->has('end_time'))
                                <span class="help-block">{{ $errors->first('end_time') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <select class="form-control" name="status">
                                <?php
                                $status = old('status', $discount->status);
                                ?>
                                @foreach(\App\Models\Discount::getDiscountStatus() as $value => $label)
                                    <?php
                                    if($value == \App\Models\Category::STATUS_ACTIVE_DB)
                                        $optionClass = 'text-green';
                                    else
                                        $optionClass = 'text-red';
                                    ?>
                                    @if($status == $value)
                                        <option class="{{ $optionClass }}" value="{{ $value }}" selected="selected">{{ $label }}</option>
                                    @else
                                        <option class="{{ $optionClass }}" value="{{ $value }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Tạo Mới</button>
                <a href="{{ action('Backend\DiscountController@adminDiscount') }}" class="btn btn-default">Quay lai</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.datetimepicker.full.min.js') }}"></script>
    <script type="text/javascript">
        $('#GenerateCodeButton').click(function() {
            var elem = $('#GenerateCodeInput');

            if(elem.val() == '')
            {
                elem.focus();
                alert('Vui lòng nhập số kí tự');
            }
            else
            {
                var number = parseInt(elem.val());

                if(number < 1 || number > 255)
                    alert('Số kí tự phải lớn hơn hoặc bằng 1 và nhỏ hơn hoặc bằng 255');
                else
                {
                    $.ajax({
                        url: '{{ action('Backend\DiscountController@generateDiscountCode') }}',
                        type: 'post',
                        data: '_token=' + $('input[name="_token"]').first().val() + '&number=' + number,
                        success: function(result) {
                            if(result)
                            {
                                $('#CodeInput').val(result);
                                elem.val('');
                            }
                            else
                                alert('Tạo mã tự động thất bại');
                        }
                    });
                }
            }
        });

        $('.DateTimePicker').datetimepicker({
            format: 'Y-m-d H:i'
        });
    </script>
@endpush