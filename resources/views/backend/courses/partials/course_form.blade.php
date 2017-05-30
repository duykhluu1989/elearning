<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($course->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCourse') }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($course->id))
            <a href="{{ action('Backend\CourseController@adminCourseItem', ['id' => $course->id]) }}" class="btn btn-primary">Danh Sách Bài Học</a>

            <?php
            $isDeletable = $course->isDeletable();
            ?>
            @if($isDeletable == true)
                <a href="{{ action('Backend\CourseController@deleteCourse', ['id' => $course->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
            @endif
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('image') ? ' has-error': '' }}">
                    <label>Ảnh</label>
                    <div>
                        <button type="button" class="btn btn-default" id="ElFinderPopupOpen"><i class="fa fa-image fa-fw"></i></button>
                        <input type="hidden" name="image" value="{{ old('image', $course->image) }}" />
                        @if(old('image', $course->image))
                            <img src="{{ old('image', $course->image) }}" width="100%" alt="Course Image" />
                        @endif
                    </div>
                </div>
            </div>
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
                <div class="form-group{{ $errors->has('name_en') ? ' has-error': '' }}">
                    <label>Tên EN</label>
                    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $course->name_en) }}" />
                    @if($errors->has('name_en'))
                        <span class="help-block">{{ $errors->first('name_en') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('category_name') ? ' has-error': '' }}">
                    <label>Chủ Đề <i>(Bắt Buộc)</i></label>
                    <input type="text" class="form-control" id="CategoryInput" name="category_name" required="required" value="{{ old('category_name', (count($course->categoryCourses) > 0 ? $course->categoryCourses[count($course->categoryCourses) - 1]->category->name : '')) }}" />
                    @if($errors->has('category_name'))
                        <span class="help-block">{{ $errors->first('category_name') }}</span>
                    @endif
                </div>
            </div>
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
                    <input type="text" class="form-control" id="UserInput" name="user_name" value="{{ old('user_name', (!empty($course->user_id) ? (!empty($course->user) ? ($course->user->profile->name . ' - ' . $course->user->email) : '') : '')) }}" />
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
                <div class="form-group">
                    <label>Loại</label>
                    <select class="form-control" name="type">
                        <?php
                        $type = old('type', $course->type);
                        ?>
                        @foreach(\App\Models\Course::getCourseType() as $value => $label)
                            @if($type == $value)
                                <option value="{{ $value }}" selected="selected">{{ $label }}</option>
                            @else
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('price') ? ' has-error': '' }}">
                    <label>Giá Tiền <i>(Bắt Buộc)</i></label>
                    <div class="input-group">
                        <input type="text" class="form-control InputForNumber" name="price" required="required" value="{{ old('price', \App\Libraries\Helpers\Utility::formatNumber($course->price)) }}" />
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
                            <input type="checkbox" id="CanBuyByPointCheckBox"<?php echo (old('point_price', $course->point_price) ? ' checked="checked"' : ''); ?> />Cho Phép Mua Khóa Học Bằng Điểm
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group{{ $errors->has('point_price') ? ' has-error': '' }}">
                    <label>Điểm</label>
                    <input type="text" class="form-control InputForNumber" id="PointPriceInput" name="point_price" value="{{ old('point_price', (!empty($course->point_price) ? \App\Libraries\Helpers\Utility::formatNumber($course->point_price) : '')) }}"<?php echo (old('point_price', $course->point_price) ? '' : ' readonly="readonly"'); ?> />
                    @if($errors->has('point_price'))
                        <span class="help-block">{{ $errors->first('point_price') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Nhãn <i>(ngăn cách nhiều nhãn bằng dấu ; chấm phẩy)</i></label>
                    <?php
                    $tags = '';
                    foreach($course->tagCourses as $tagCourse)
                    {
                        if($tags == '')
                            $tags = $tagCourse->tag->name;
                        else
                            $tags .= ';' . $tagCourse->tag->name;
                    }
                    ?>
                    <input type="text" class="form-control" id="TagInput" name="tags" value="{{ old('tags', $tags) }}" />
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
                                <input type="text" class="form-control" name="slug" value="{{ old('slug', $course->slug) }}" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Mô Tả Ngắn</label>
                                <input type="text" class="form-control" name="short_description" value="{{ old('short_description', $course->short_description) }}" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('description') ? ' has-error': '' }}">
                                <label>Mô Tả <i>(bắt buộc)</i></label>
                                @if($errors->has('description'))
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                @endif
                                <textarea class="form-control TextEditorInput" name="description">{{ old('description', $course->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Liên Kết Tĩnh EN</label>
                                <input type="text" class="form-control" name="slug_en" value="{{ old('slug_en', $course->slug_en) }}" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Mô Tả Ngắn EN</label>
                                <input type="text" class="form-control" name="short_description_en" value="{{ old('short_description_en', $course->short_description_en) }}" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Mô Tả EN</label>
                                <textarea class="form-control TextEditorInput" name="description_en">{{ old('description_en', $course->description_en) }}</textarea>
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

        @if(!empty($course->id))
            <a href="{{ action('Backend\CourseController@adminCourseItem', ['id' => $course->id]) }}" class="btn btn-primary">Danh Sách Bài Học</a>

            @if($isDeletable == true)
                <a href="{{ action('Backend\CourseController@deleteCourse', ['id' => $course->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
            @endif
        @endif
    </div>
</div>
{{ csrf_field() }}

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/colorbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tag-editor.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.colorbox-min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.caret.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.tag-editor.min.js') }}"></script>
    <script src="{{ asset('packages/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        var elFinderSelectedFile;

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
            nonbreaking_force_tab: true,
            convert_urls: false,
            file_picker_callback : function(callback, value, meta) {
                tinymce.activeEditor.windowManager.open({
                    file: '{{ action('Backend\ElFinderController@tinymce') }}',
                    title: 'Thư Viện',
                    width: 1200,
                    height: 600
                }, {
                    oninsert: function(file, elf) {
                        var url, reg, info;
                        url = file.url;
                        reg = /\/[^/]+?\/\.\.\//;
                        while(url.match(reg))
                            url = url.replace(reg, '/');
                        info = file.name + ' (' + elf.formatSize(file.size) + ')';
                        if(meta.filetype == 'file')
                            callback(url, {text: info, title: info});
                        if(meta.filetype == 'image')
                            callback(url, {alt: info});
                        if(meta.filetype == 'media')
                            callback(url);
                    }
                });
                return false;
            }
        });

        $('#UserInput').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '{{ action('Backend\UserController@autoCompleteUser') }}',
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
                $(this).val(ui.item.name + ' - ' + ui.item.email);
                return false;
            }
        }).autocomplete('instance')._renderItem = function(ul, item) {
            return $('<li>').append('<a>' + item.name + ' - ' + item.email + '</a>').appendTo(ul);
        };

        $('#CategoryInput').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '{{ action('Backend\CourseController@autoCompleteCategory') }}',
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

        $('#CanBuyByPointCheckBox').click(function() {
            if($(this).prop('checked'))
                $('#PointPriceInput').removeAttr('readonly').val(1);
            else
                $('#PointPriceInput').val('').prop('readonly', 'readonly');
        });

        $('#TagInput').tagEditor({
            delimiter: ';',
            sortable: false,
            autocomplete: {
                minLength: 3,
                delay: 1000,
                source: function(request, response) {
                    $.ajax({
                        url: '{{ action('Backend\CourseController@autoCompleteTag') }}',
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
                }
            }
        });

        $('#ElFinderPopupOpen').click(function() {
            elFinderSelectedFile = $(this).parent().find('input').first();

            $.colorbox({
                href: '{{ action('Backend\ElFinderController@popup') }}',
                iframe: true,
                width: '1200',
                height: '600',
                closeButton: false
            });
        });

        function elFinderProcessSelectedFile(fileUrl)
        {
            elFinderSelectedFile.val(fileUrl);

            if(elFinderSelectedFile.parent().find('img').length > 0)
                elFinderSelectedFile.parent().find('img').first().prop('src', fileUrl);
            else
            {
                elFinderSelectedFile.parent().append('' +
                    '<img src="' + fileUrl + '" width="100%" alt="Course Image" />' +
                '');
            }
        }

        setInterval(function() {
            $.ajax({
                url: '{{ action('Backend\HomeController@refreshCsrfToken') }}',
                type: 'post',
                data: '_token=' + $('input[name="_token"]').first().val(),
                success: function(result) {
                    if(result)
                    {
                        $('input[name="_token"]').each(function() {
                            $(this).val(result);
                        });
                    }
                }
            });
        }, 60000);
    </script>
@endpush