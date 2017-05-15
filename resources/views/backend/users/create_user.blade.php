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
                            <input type="text" class="form-control" name="username" required="required" value="{{ old('username', $user->username) }}" />
                            @if($errors->has('name'))
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