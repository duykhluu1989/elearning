<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($role->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\RoleController@adminRole') }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($role->id) && count($role->userRoles) == 0 && $role->name != \App\Models\Role::ROLE_ADMINISTRATOR)
            <a href="{{ action('Backend\RoleController@deleteRole', ['id' => $role->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Vai Trò <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $role->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Mô Tả</label>
                    <textarea class="form-control" name="description">{{ old('description', $role->description) }}</textarea>
                </div>
            </div>

            <?php
            $allowedRoutes = array();
            if(!empty($role->permission))
                $allowedRoutes = json_decode($role->permission, true);
            $allowedRoutes = old('permission', $allowedRoutes);

            $routes = Route::getRoutes();
            ?>

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Phân Quyền</label>
                    <div class="row">
                    @foreach($routes as $route)
                        @if(in_array('permission', $route->middleware()))
                            @foreach($route->methods() as $method)
                                @if(strtolower($method) != 'head')
                                    <?php
                                    $routeName = $method . ' - ' . $route->uri();
                                    ?>
                                    <div class="col-sm-3">
                                        <div class="checkbox">
                                            <label>
                                                <input name="permission[]" type="checkbox" value="{{ $routeName }}"<?php echo (in_array($routeName, $allowedRoutes) ? ' checked="checked"' : ''); ?> />{{ $routeName }}
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($role->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\RoleController@adminRole') }}" class="btn btn-default">Quay lai</a>

        @if(!empty($role->id) && count($role->userRoles) == 0 && $role->name != \App\Models\Role::ROLE_ADMINISTRATOR)
            <a href="{{ action('Backend\RoleController@deleteRole', ['id' => $role->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
</div>
{{ csrf_field() }}