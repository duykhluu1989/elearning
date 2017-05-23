@extends('backend.layouts.main')

@section('page_heading', 'Thành Viên Mới')

@section('section')

    <form action="{{ action('Backend\UserController@createUser') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Tạo Mới</button>
                <a href="{{ action('Backend\UserController@adminUser') }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('username') ? ' has-error': '' }}">
                            <label>Tên Tài Khoản <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="username" required="required" value="{{ old('username') }}" />
                            @if($errors->has('username'))
                                <span class="help-block">{{ $errors->first('username') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('email') ? ' has-error': '' }}">
                            <label>Email <i>(bắt buộc)</i></label>
                            <input type="email" class="form-control" name="email" required="required" value="{{ old('email') }}" />
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
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('password') ? ' has-error': '' }}">
                            <label>Mật Khẩu <i>(bắt buộc)</i></label>
                            <input type="password" class="form-control" name="password" required="required" value="{{ old('password') }}" />
                            @if($errors->has('password'))
                                <span class="help-block">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('re_password') ? ' has-error': '' }}">
                            <label>Xác Nhận Mật Khẩu <i>(bắt buộc)</i></label>
                            <input type="password" class="form-control" name="re_password" required="required" value="{{ old('re_password') }}" />
                            @if($errors->has('re_password'))
                                <span class="help-block">{{ $errors->first('re_password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('roles') ? ' has-error': '' }}">
                            <label>Vai Trò</label>
                            @if($errors->has('roles'))
                                <span class="help-block">{{ $errors->first('roles') }}</span>
                            @endif
                            <?php
                            $assignedRoles = array();
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
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Tạo Mới</button>
                <a href="{{ action('Backend\UserController@adminUser') }}" class="btn btn-default">Quay lai</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop