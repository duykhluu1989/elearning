<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($apply->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CertificateController@adminCertificateApply')) }}" class="btn btn-default">Quay Lại</a>
    </div>
    <div class="box-body">
        @if(!empty($apply->id))
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Thời Gian Đăng Kí</label>
                        <span class="form-control no-border">{{ $apply->created_at }}</span>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $apply->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('certificate_name') ? ' has-error': '' }}">
                    <label>Chứng Chỉ <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" id="CertificateInput" name="certificate_name" required="required" value="{{ old('certificate_name', (!empty($apply->certificate_id) ? (!empty($apply->certificate) ? $apply->certificate->name : '') : '')) }}" />
                    @if($errors->has('certificate_name'))
                        <span class="help-block">{{ $errors->first('certificate_name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Trạng Thái</label>
                    <?php
                    $status = old('status', $apply->status);
                    ?>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="status" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($status == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Models\CertificateApply::STATUS_ACTIVE_LABEL }}" data-off="{{ \App\Models\CertificateApply::STATUS_INACTIVE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('phone') ? ' has-error': '' }}">
                    <label>Số Điện Thoại <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="phone" required="required" value="{{ old('phone', $apply->phone) }}" />
                    @if($errors->has('phone'))
                        <span class="help-block">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($apply->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CertificateController@adminCertificateApply')) }}" class="btn btn-default">Quay Lại</a>
    </div>
</div>
{{ csrf_field() }}

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
    <script type="text/javascript">
        $('#CertificateInput').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '{{ action('Backend\CertificateController@autoCompleteCertificate') }}',
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
                $(this).val(ui.item.name);
                return false;
            }
        }).autocomplete('instance')._renderItem = function(ul, item) {
            return $('<li>').append('<a>' + item.name + '</a>').appendTo(ul);
        };
    </script>
@endpush