@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Giảng Viên - ' . $teacher->username)

@section('section')

    <form action="{{ action('Backend\TeacherController@editTeacher', ['id' => $teacher->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUserTeacher')) }}" class="btn btn-default">Quay Lại</a>
                <a href="{{ action('Backend\TeacherController@adminTeacherTransaction', ['id' => $teacher->id]) }}" class="btn btn-primary">Lịch Sử Hoa Hồng</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <?php
                            $status = old('status', $teacher->teacherInformation->status);
                            ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="status" value="{{ \App\Models\Collaborator::STATUS_ACTIVE_DB }}"<?php echo ($status == \App\Models\Collaborator::STATUS_ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Models\Collaborator::STATUS_ACTIVE_LABEL }}" data-off="{{ \App\Models\Collaborator::STATUS_INACTIVE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tổ Chức</label>
                            <?php
                            $organization = old('organization', $teacher->teacherInformation->organization);
                            ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="organization" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($organization == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Models\Teacher::ORGANIZATION_LABEL }}" data-off="{{ \App\Models\Teacher::PERSONAL_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Hoa Hồng Hiện Tại</label>
                            <span class="form-control no-border">{{ \App\Libraries\Helpers\Utility::formatNumber($teacher->teacherInformation->current_commission) . ' VND' }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Hoa Hồng Tổng</label>
                            <span class="form-control no-border">{{ \App\Libraries\Helpers\Utility::formatNumber($teacher->teacherInformation->total_commission) . ' VND' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUserTeacher')) }}" class="btn btn-default">Quay Lại</a>
                <a href="{{ action('Backend\TeacherController@adminTeacherTransaction', ['id' => $teacher->id]) }}" class="btn btn-primary">Lịch Sử Hoa Hồng</a>
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