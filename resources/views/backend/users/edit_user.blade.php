@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Thành Viên - ' . $user->username)

@section('section')

    <form action="{{ action('Backend\UserController@editUser', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ action('Backend\UserController@adminUser') }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Thông Tin Tài Khoản</a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Thông Tin Thành Viên</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group{{ $errors->has('avatar') ? ' has-error': '' }}">
                                        <label>Ảnh Đại Diện <i>(200 x 200)</i></label>
                                        <input type="file" class="form-control" name="avatar" accept="{{ implode(', ', \App\Libraries\Helpers\Utility::getValidImageExt(true)) }}" />
                                        @if($errors->has('avatar'))
                                            <span class="help-block">{{ $errors->first('avatar') }}</span>
                                        @endif
                                        @if(!empty($user->avatar))
                                            <img src="{{ $user->avatar }}" width="150px" />
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('username') ? ' has-error': '' }}">
                                        <label>Tên Tài Khoản <i>(bắt buộc)</i></label>
                                        <input type="text" class="form-control" name="username" required="required" value="{{ old('username', $user->username) }}" />
                                        @if($errors->has('username'))
                                            <span class="help-block">{{ $errors->first('username') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('email') ? ' has-error': '' }}">
                                        <label>Email <i>(bắt buộc)</i></label>
                                        <input type="email" class="form-control" name="email" required="required" value="{{ old('email', $user->email) }}" />
                                        @if($errors->has('email'))
                                            <span class="help-block">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Trạng Thái</label>
                                        <select class="form-control" name="status">
                                            <?php
                                            $status = old('status', $user->status);
                                            ?>
                                            @foreach(\App\Models\User::getUserStatus() as $value => $label)
                                                <?php
                                                if($value == \App\Models\User::STATUS_ACTIVE_DB)
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
                                    <div class="form-group{{ $errors->has('admin') ? ' has-error': '' }}">
                                        <label>Thành Viên</label>
                                        <select class="form-control" name="admin">
                                            <?php
                                            $admin = old('admin', $user->admin);
                                            ?>
                                            @foreach(\App\Models\User::getUserAdmin() as $value => $label)
                                                @if($admin == $value)
                                                    <option value="{{ $value }}" selected="selected">{{ $label }}</option>
                                                @else
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if($errors->has('admin'))
                                            <span class="help-block">{{ $errors->first('admin') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Thời Gian Khởi Tạo</label>
                                        <span class="form-control no-border">{{ $user->created_at }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('password') ? ' has-error': '' }}">
                                        <label>Mật Khẩu Mới</label>
                                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" />
                                        @if($errors->has('password'))
                                            <span class="help-block">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('re_password') ? ' has-error': '' }}">
                                        <label>Xác Nhận Mật Khẩu Mới</label>
                                        <input type="password" class="form-control" name="re_password" value="{{ old('re_password') }}" />
                                        @if($errors->has('re_password'))
                                            <span class="help-block">{{ $errors->first('re_password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Vai Trò <i>(bắt buộc)</i></label>
                                        <?php
                                        $assignedRoles = array();
                                        foreach($user->userRoles as $userRole)
                                            $assignedRoles[] = $userRole->role_id;
                                        $assignedRoles = old('roles', $assignedRoles);
                                        ?>
                                        <div class="row">
                                            @foreach($roles as $id => $name)
                                                <div class="col-sm-3">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input name="roles[]" type="checkbox" value="{{ $id }}"<?php echo (in_array($id, $assignedRoles) ? ' checked="checked"' : ''); ?> />{{ $name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('first_name') ? ' has-error': '' }}">
                                        <label>Tên</label>
                                        <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->profile->first_name) }}" />
                                        @if($errors->has('first_name'))
                                            <span class="help-block">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Họ</label>
                                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->profile->last_name) }}" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Giới Tính</label>
                                        <select class="form-control" name="gender">
                                            <?php
                                            $gender = old('gender', (!empty($user->profile) ? $user->profile->gender : ''));
                                            ?>
                                            @foreach(\App\Models\Profile::getProfileGender() as $value => $label)
                                                @if($gender == $value)
                                                    <option value="{{ $value }}" selected="selected">{{ $label }}</option>
                                                @else
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group{{ $errors->has('phone') ? ' has-error': '' }}">
                                        <label>Số Điện Thoại</label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->profile->phone) }}" />
                                        @if($errors->has('phone'))
                                            <span class="help-block">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group{{ $errors->has('birthday') ? ' has-error': '' }}">
                                        <label>Ngày Sinh</label>
                                        <input type="text" class="form-control DatePicker" name="birthday" value="{{ old('birthday', $user->profile->birthday) }}" />
                                        @if($errors->has('birthday'))
                                            <span class="help-block">{{ $errors->first('birthday') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Địa Chỉ</label>
                                        <input type="text" class="form-control" name="address" value="{{ old('address', $user->profile->address) }}" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Mô Tả</label>
                                        <textarea class="form-control" name="description">{{ old('description', $user->profile->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ action('Backend\UserController@adminUser') }}" class="btn btn-default">Quay lai</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop