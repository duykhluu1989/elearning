<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($course->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCourse') }}" class="btn btn-default">Quay Lại</a>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('code') ? ' has-error': '' }}">
                    <label>Mã <i>(Bắt Buộc)</i></label>
                    <input type="text" class="form-control" name="code" required="required" value="{{ old('code', $course->code) }}" />
                    @if($errors->has('code'))
                        <span class="help-block">{{ $errors->first('code') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('user_name') ? ' has-error': '' }}">
                    <label>Giảng Viên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" id="UserInput" name="user_name" value="{{ old('user_name', (!empty($course->user_id) ? (!empty($course->user) ? $category->user->profile->name : '') : '')) }}" />
                    @if($errors->has('user_name'))
                        <span class="help-block">{{ $errors->first('user_name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Trạng Thái</label>
                    <select class="form-control" name="status">
                        <?php
                        $status = old('status', $course->status);
                        ?>
                        @foreach(\App\Models\Course::getCourseStatus() as $value => $label)
                            <?php
                            if($value == \App\Models\Course::STATUS_PUBLISH_DB)
                                $optionClass = 'text-green';
                            else if($value == \App\Models\Course::STATUS_FINISH_DB)
                                $optionClass = 'text-light-blue';
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
                <div class="form-group{{ $errors->has('level') ? ' has-error': '' }}">
                    <label>Cấp Độ</label>
                    <select class="form-control" name="level_id">
                        <?php
                        $levelId = old('level_id', $course->level_id);
                        ?>
                        @foreach($levels as $id => $name)
                            @if($levelId == $id)
                                <option value="{{ $id }}" selected="selected">{{ $name }}</option>
                            @else
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @if($errors->has('level'))
                        <span class="help-block">{{ $errors->first('level') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('type') ? ' has-error': '' }}">
                    <label>Loại</label>
                    <select class="form-control" name="type">
                        <?php
                        $type = old('type', $course->type);
                        ?>
                        @foreach(\App\Models\Course::getCourseType() as $value => $label)
                            @if($status == $value)
                                <option value="{{ $value }}" selected="selected">{{ $label }}</option>
                            @else
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                    @if($errors->has('type'))
                        <span class="help-block">{{ $errors->first('type') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('price') ? ' has-error': '' }}">
                    <label>Giá Tiền <i>(Bắt Buộc)</i></label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="price" required="required" value="{{ old('price', $course->price) }}" />
                        <span class="input-group-addon">VND</span>
                    </div>
                    @if($errors->has('price'))
                        <span class="help-block">{{ $errors->first('price') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Mua Bằng Điểm</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="can_buy_by_point" id="CanBuyByPointCheckBox" value="can_buy_by_point" />Có Thể Mua Khóa Học Bằng Điểm
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('point_price') ? ' has-error': '' }}">
                    <label>Điểm</label>
                    <input type="text" class="form-control" id="PointPriceInput" name="point_price" value="{{ old('point_price', $course->point_price) }}" />
                    @if($errors->has('point_price'))
                        <span class="help-block">{{ $errors->first('point_price') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Mô Tả Tiếng Việt</a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Mô Tả Tiếng Anh</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                                <label>Tên <i>(bắt buộc)</i></label>
                                <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $course->name) }}" />
                                @if($errors->has('name'))
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('slug') ? ' has-error': '' }}">
                                <label>Liên Kết Tĩnh</label>
                                <input type="text" class="form-control" name="slug" value="{{ old('slug', $course->slug) }}" />
                                @if($errors->has('slug'))
                                    <span class="help-block">{{ $errors->first('slug') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('short_description') ? ' has-error': '' }}">
                                <label>Mô Tả Ngắn</label>
                                <input type="text" class="form-control" name="short_description" value="{{ old('short_description', $course->short_description) }}" />
                                @if($errors->has('short_description'))
                                    <span class="help-block">{{ $errors->first('short_description') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('description') ? ' has-error': '' }}">
                                <label>Mô Tả <i>(bắt buộc)</i></label>
                                <textarea class="form-control TextEditorInput" name="description" required="required">{{ old('description', $course->description) }}</textarea>
                                @if($errors->has('description'))
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('name_en') ? ' has-error': '' }}">
                                <label>Tên EN</label>
                                <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $course->name_en) }}" />
                                @if($errors->has('name_en'))
                                    <span class="help-block">{{ $errors->first('name_en') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('slug_en') ? ' has-error': '' }}">
                                <label>Liên Kết Tĩnh EN</label>
                                <input type="text" class="form-control" name="slug_en" value="{{ old('slug_en', $course->slug_en) }}" />
                                @if($errors->has('slug_en'))
                                    <span class="help-block">{{ $errors->first('slug_en') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('short_description_en') ? ' has-error': '' }}">
                                <label>Mô Tả Ngắn EN</label>
                                <input type="text" class="form-control" name="short_description_en" value="{{ old('short_description_en', $course->short_description_en) }}" />
                                @if($errors->has('short_description_en'))
                                    <span class="help-block">{{ $errors->first('short_description_en') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('description_en') ? ' has-error': '' }}">
                                <label>Mô Tả EN</label>
                                <textarea class="form-control TextEditorInput" name="description_en" required="required">{{ old('description_en', $course->description_en) }}</textarea>
                                @if($errors->has('description_en'))
                                    <span class="help-block">{{ $errors->first('description_en') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($course->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCourse') }}" class="btn btn-default">Quay lai</a>
    </div>
</div>
{{ csrf_field() }}

@push('scripts')
    <script src="{{ asset('packages/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: '.TextEditorInput',
            height: 600,
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons',
            image_advtab: true,
            nonbreaking_force_tab: true
        });

        $('#UserInput').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '{{ action('Backend\UserController@autoCompleteUser') }}',
                    type: 'post',
                    data: '_token={{ csrf_token() }}&term=' + request.term,
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
            return $('<li>').append('<a>' + item.name + ' - ' + item.username + ' - ' + item.email + '</a>').appendTo(ul);
        };

        $('#CanBuyByPointCheckBox').click(function() {
            if($(this).prop('checked'))
                $('#PointPriceInput').removeAttr('readonly').val(0);
            else
                $('#PointPriceInput').val('').prop('readonly', 'readonly');
        });
    </script>
@endpush