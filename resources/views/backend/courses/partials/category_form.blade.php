<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($category->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCategory') }}" class="btn btn-default">Quay Lại</a>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $category->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('name_en') ? ' has-error': '' }}">
                    <label>Tên EN</label>
                    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $category->name_en) }}" />
                    @if($errors->has('name_en'))
                        <span class="help-block">{{ $errors->first('name_en') }}</span>
                    @endif
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
                    <select class="form-control" name="status">
                        <?php
                        $status = old('status', $category->status);
                        ?>
                        @foreach(\App\Models\Category::getCategoryStatus() as $value => $label)
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
                <div class="form-group{{ $errors->has('order') ? ' has-error': '' }}">
                    <label>Thứ Tự <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="order" required="required" value="{{ old('order', $category->order) }}" />
                    @if($errors->has('order'))
                        <span class="help-block">{{ $errors->first('order') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('slug') ? ' has-error': '' }}">
                    <label>Liên Kết Tĩnh</label>
                    <input type="text" class="form-control" name="slug" value="{{ old('slug', $category->slug) }}" />
                    @if($errors->has('slug'))
                        <span class="help-block">{{ $errors->first('slug') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('slug_en') ? ' has-error': '' }}">
                    <label>Liên Kết Tĩnh EN</label>
                    <input type="text" class="form-control" name="slug_en" value="{{ old('slug_en', $category->slug_en) }}" />
                    @if($errors->has('slug_en'))
                        <span class="help-block">{{ $errors->first('slug_en') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($category->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCategory') }}" class="btn btn-default">Quay lai</a>
    </div>
</div>
{{ csrf_field() }}

@push('scripts')
    <script type="text/javascript">
        $('#ParentCategoryInput').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '{{ action('Backend\CourseController@autoCompleteCategory') }}',
                    type: 'post',
                    data: '_token={{ csrf_token() }}<?php echo !empty($category->id) ? ('&except=' . $category->id) : ''; ?>&term=' + request.term,
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