<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($category->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCategory') }}" class="btn btn-default">Quay Lại</a>

        <?php
        $isDeletable = $category->isDeletable();
        ?>
        @if(!empty($category->id) && $isDeletable == true)
            <a href="{{ action('Backend\CourseController@deleteCategory', ['id' => $category->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $category->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Tên EN</label>
                    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $category->name_en) }}" />
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('code') ? ' has-error': '' }}">
                    <label>Mã <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="code" required="required" value="{{ old('code', $category->code) }}" />
                    @if($errors->has('code'))
                        <span class="help-block">{{ $errors->first('code') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('parent_name') ? ' has-error': '' }}">
                    <label>Chủ Đề Cha</label>
                    <input type="text" class="form-control" id="ParentCategoryInput" name="parent_name" value="{{ old('parent_name', (!empty($category->parent_id) ? (!empty($category->parentCategory) ? $category->parentCategory->name : '') : '')) }}" />
                    @if($errors->has('parent_name'))
                        <span class="help-block">{{ $errors->first('parent_name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Trạng Thái</label>
                    <?php
                    $status = old('status', $category->status);
                    ?>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="status" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}" <?php echo ($status == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('order') ? ' has-error': '' }}">
                    <label>Thứ Tự <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="order" required="required" value="{{ old('order', $category->order) }}" />
                    @if($errors->has('order'))
                        <span class="help-block">{{ $errors->first('order') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Liên Kết Tĩnh</label>
                    <input type="text" class="form-control" name="slug" value="{{ old('slug', $category->slug) }}" />
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Liên Kết Tĩnh EN</label>
                    <input type="text" class="form-control" name="slug_en" value="{{ old('slug_en', $category->slug_en) }}" />
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($category->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCategory') }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($category->id) && $isDeletable == true)
            <a href="{{ action('Backend\CourseController@deleteCategory', ['id' => $category->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
</div>
{{ csrf_field() }}

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
    <script type="text/javascript">
        $('#ParentCategoryInput').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '{{ action('Backend\CourseController@autoCompleteCategory') }}',
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '<?php echo !empty($category->id) ? ('&except=' . $category->id) : ''; ?>&term=' + request.term,
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