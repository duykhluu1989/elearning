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
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('code') ? ' has-error': '' }}">
                            <label>Mã <i>(bắt buộc nếu không chọn tạo nhiều mã)</i></label>
                            <input type="text" class="form-control" id="CodeInput" name="code" value="{{ old('code', $discount->code) }}"<?php echo (old('create_multi_number') ? ' readonly="readonly"' : ' required="required"'); ?> />
                            @if($errors->has('code'))
                                <span class="help-block">{{ $errors->first('code') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tạo Mã Tự Động <i>(nhập số kí tự của mã: 1 - 255)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="GenerateCodeInput"<?php echo (old('create_multi_number') ? ' readonly="readonly"' : ''); ?> />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" id="GenerateCodeButton"<?php echo (old('create_multi_number') ? ' disabled="disabled"' : ''); ?>>Tạo Mã</button>
                                </span>
                            </div>
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
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tạo Nhiều Mã</label>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="CreateMultiCheckBox"<?php echo (old('create_multi_number') ? ' checked="checked"' : ''); ?> />Tạo Nhiều Mã Cùng Thuộc Tính
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('create_multi_character_number') ? ' has-error': '' }}">
                            <label>Tạo Nhiều Mã - Số Kí Tự</label>
                            <input type="text" class="form-control" id="CreateMultiCharacterNumberInput" name="create_multi_character_number" value="{{ old('create_multi_character_number') }}"<?php echo (old('create_multi_number') ? ' required="required"' : ' readonly="readonly"'); ?> />
                            @if($errors->has('create_multi_character_number'))
                                <span class="help-block">{{ $errors->first('create_multi_character_number') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('create_multi_number') ? ' has-error': '' }}">
                            <label>Tạo Nhiều Mã - Số Lượng Mã</label>
                            <input type="text" class="form-control" id="CreateMultiNumberInput" name="create_multi_number" value="{{ old('create_multi_number') }}"<?php echo (old('create_multi_number') ? ' required="required"' : ' readonly="readonly"'); ?> />
                            @if($errors->has('create_multi_number'))
                                <span class="help-block">{{ $errors->first('create_multi_number') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('minimum_order_amount') ? ' has-error': '' }}">
                            <label>Giá Trị Đơn Hàng Tối Thiểu</label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" name="minimum_order_amount" value="{{ old('minimum_order_amount', (!empty($discount->minimum_order_amount) ? \App\Libraries\Helpers\Utility::formatNumber($discount->minimum_order_amount) : '')) }}" />
                                <span class="input-group-addon">VND</span>
                            </div>
                            @if($errors->has('minimum_order_amount'))
                                <span class="help-block">{{ $errors->first('minimum_order_amount') }}</span>
                            @endif
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
                            <label>Loại Giảm Giá</label>
                            <select class="form-control" id="TypeDropDown" name="type">
                                <?php
                                $type = old('type', $discount->type);
                                ?>
                                @foreach(\App\Models\Discount::getDiscountType() as $value => $label)
                                    @if($type == $value)
                                        <option value="{{ $value }}" selected="selected">{{ $label }}</option>
                                    @else
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('value') ? ' has-error': '' }}">
                            <label>Giá Trị Giảm Giá <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="ValueInput" name="value" required="required" value="{{ old('value', ($discount->type == \App\Models\Discount::TYPE_FIX_AMOUNT_DB ? \App\Libraries\Helpers\Utility::formatNumber($discount->value) : $discount->value)) }}" />
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
                                <input type="text" class="form-control InputForNumber" id="ValueLimitInput" name="value_limit" value="{{ old('value_limit', (!empty($discount->value_limit) ? \App\Libraries\Helpers\Utility::formatNumber($discount->value_limit) : '')) }}"<?php echo ($type == \App\Models\Discount::TYPE_FIX_AMOUNT_DB ? ' readonly="readonly"' : ''); ?> />
                                <span class="input-group-addon">VND</span>
                            </div>
                            @if($errors->has('value_limit'))
                                <span class="help-block">{{ $errors->first('value_limit') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('usage_limit') ? ' has-error': '' }}">
                            <label>Số Lần Sử Dụng Tổng <i>(để trống là không giới hạn)</i></label>
                            <input type="text" class="form-control" name="usage_limit" value="{{ old('usage_limit', $discount->usage_limit) }}" />
                            @if($errors->has('usage_limit'))
                                <span class="help-block">{{ $errors->first('usage_limit') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Thành Viên Được Sử Dụng</label>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="FixUsageUserCheckBox"<?php echo (old('username', $discount->user_id) ? ' checked="checked"' : ''); ?> />Chỉ Cho Phép Thành Viên Được Chỉ Định Sử Dụng
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('username') ? ' has-error': '' }}">
                            <label>Thành Viên Chỉ Định</label>
                            <input type="text" class="form-control" id="UsernameInput" name="username" value="{{ old('username', (!empty($discount->user_id) ? (!empty($discount->user) ? $discount->user->username : '') : '')) }}"<?php echo (old('username', $discount->user_id) ? ' required="required"' : ' readonly="readonly"'); ?> />
                            @if($errors->has('username'))
                                <span class="help-block">{{ $errors->first('username') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('usage_unique') ? ' has-error': '' }}">
                            <label>Số Lần Sử Dụng Mỗi Thành Viên <i>(để trống là không giới hạn)</i></label>
                            <input type="text" class="form-control" id="UsageUniqueInput" name="usage_unique" value="{{ old('usage_unique', $discount->usage_unique) }}"<?php echo (old('username', $discount->user_id) ? ' readonly="readonly"' : ''); ?> />
                            @if($errors->has('usage_unique'))
                                <span class="help-block">{{ $errors->first('usage_unique') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group{{ $errors->has('campaign_code') ? ' has-error': '' }}">
                            <label>Mã Chương Trình <i>(mã giảm giá cùng chương trình thì mỗi thành viên chỉ được sử dụng 1 mã)</i></label>
                            <input type="text" class="form-control" id="CampaignCodeInput" name="campaign_code" value="{{ old('usage_unique', $discount->usage_unique) }}"<?php echo (old('username', $discount->user_id) ? ' readonly="readonly"' : ''); ?> />
                            @if($errors->has('campaign_code'))
                                <span class="help-block">{{ $errors->first('campaign_code') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Mô Tả</label>
                            <textarea class="form-control" name="description">{{ old('description', $discount->description) }}</textarea>
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
            format: 'Y-m-d H:i:s'
        });

        setValueInputFormatNumber(true);

        $('#TypeDropDown').change(function() {
            setValueInputFormatNumber(false);
        });

        function setValueInputFormatNumber(init)
        {
            var valueInputElem = $('#ValueInput');

            if($('#TypeDropDown').val() == '{{ \App\Models\Discount::TYPE_FIX_AMOUNT_DB }}')
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

        $('#FixUsageUserCheckBox').click(function() {
            if($(this).prop('checked'))
            {
                $('#UsernameInput').removeAttr('readonly').prop('required', 'required');
                $('#UsageUniqueInput').val('').prop('readonly', 'readonly');
                $('#CampaignCodeInput').val('').prop('readonly', 'readonly');
            }
            else
            {
                $('#UsernameInput').val('').removeAttr('required').prop('readonly', 'readonly');
                $('#UsageUniqueInput').removeAttr('readonly');
                $('#CampaignCodeInput').removeAttr('readonly');
            }
        });

        $('#CreateMultiCheckBox').click(function() {
            if($(this).prop('checked'))
            {
                $('#CreateMultiCharacterNumberInput').removeAttr('readonly').prop('required', 'required').val(1);
                $('#CreateMultiNumberInput').removeAttr('readonly').prop('required', 'required').val(1);
                $('#CodeInput').val('').removeAttr('required').prop('readonly', 'readonly');
                $('#GenerateCodeInput').val('').prop('readonly', 'readonly');
                $('#GenerateCodeButton').prop('disabled', 'disabled');
            }
            else
            {
                $('#CreateMultiCharacterNumberInput').val('').removeAttr('required').prop('readonly', 'readonly');
                $('#CreateMultiNumberInput').val('').removeAttr('required').prop('readonly', 'readonly');
                $('#CodeInput').removeAttr('readonly').prop('required', 'required');
                $('#GenerateCodeInput').removeAttr('readonly');
                $('#GenerateCodeButton').removeAttr('disabled');
            }
        });

        $('#UsernameInput').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '{{ action('Backend\UserController@autoCompleteUser') }}',
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '&term=' + request.term,
                    success: function(result) {
                        if(result)
                        {
                            result = JSON.parse(result);
                            response(result);
                        }
                    }
                });
            },
            select: function(event, ui) {
                $(this).val(ui.item.username);
                return false;
            }
        }).autocomplete('instance')._renderItem = function(ul, item) {
            return $('<li>').append('<a>' + item.username + '</a>').appendTo(ul);
        };
    </script>
@endpush