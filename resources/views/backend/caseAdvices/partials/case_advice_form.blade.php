<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($case->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CaseAdviceController@adminCaseAdvice')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($case->id))
            <a href="{{ action('Backend\CaseAdviceController@adminCaseAdviceStep', ['id' => $case->id]) }}" class="btn btn-primary">Danh Sách Bước Giải Quyết</a>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $case->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Tên EN</label>
                    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $case->name_en) }}" />
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Trạng Thái</label>
                    <?php
                    $status = old('status', $case->status);
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
                <div class="form-group">
                    <label>Loại</label>
                    <?php
                    $type = old('type', $case->type);
                    ?>
                    <div>
                        @foreach(\App\Models\CaseAdvice::getCaseAdviceType() as $value => $label)
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
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('phone') ? ' has-error': '' }}">
                    <label>Điện Thoại Tư Vấn</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $case->phone) }}" />
                    @if($errors->has('phone'))
                        <span class="help-block">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('adviser') ? ' has-error': '' }}">
                    <label>Người Tư Vấn</label>
                    <input type="text" class="form-control" name="adviser" value="{{ old('adviser', $case->adviser) }}" />
                    @if($errors->has('adviser'))
                        <span class="help-block">{{ $errors->first('adviser') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('order') ? ' has-error': '' }}">
                    <label>Thứ Tự <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="order" required="required" value="{{ old('order', $case->order) }}" />
                    @if($errors->has('order'))
                        <span class="help-block">{{ $errors->first('order') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="box-body">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><b>Nội Dung Tiếng Việt</b></a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><b>Nội Dung Tiếng Anh</b></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Liên Kết Tĩnh</label>
                                <input type="text" class="form-control" name="slug" value="{{ old('slug', $case->slug) }}" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('description') ? ' has-error': '' }}">
                                <label>Mô Tả <i>(bắt buộc)</i></label>
                                @if($errors->has('description'))
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                @endif
                                <textarea class="form-control" rows="10" name="description">{{ old('description', $case->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Liên Kết Tĩnh EN</label>
                                <input type="text" class="form-control" name="slug_en" value="{{ old('slug_en', $case->slug_en) }}" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Mô Tả EN</label>
                                <textarea class="form-control" rows="10" name="description_en">{{ old('description_en', $case->description_en) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($case->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CaseAdviceController@adminCaseAdvice')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($case->id))
            <a href="{{ action('Backend\CaseAdviceController@adminCaseAdviceStep', ['id' => $case->id]) }}" class="btn btn-primary">Danh Sách Bước Giải Quyết</a>
        @endif
    </div>
</div>
{{ csrf_field() }}

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
@endpush