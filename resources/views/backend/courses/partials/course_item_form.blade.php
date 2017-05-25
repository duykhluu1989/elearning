<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($courseItem->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCourseItem', ['id' => $courseItem->course_id]) }}" class="btn btn-default">Quay Lại</a>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="page-header">Bài Học {{ $courseItem->number }}</h2>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Loại</label>
                    <select class="form-control" id="TypeSelect" name="type">
                        <?php
                        $type = old('type', $courseItem->type);
                        ?>
                        @foreach(\App\Models\CourseItem::getCourseItemType() as $value => $label)
                            @if($type == $value)
                                <option value="{{ $value }}" selected="selected">{{ $label }}</option>
                            @else
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
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
                            <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                                <label>Tên <i>(bắt buộc)</i></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $courseItem->name) }}" />
                                @if($errors->has('name'))
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12 CourseItemTextTypeInput" style="{{ $type != \App\Models\CourseItem::TYPE_TEXT_DB ? 'display: none' : '' }}">
                            <div class="form-group{{ $errors->has('content') ? ' has-error': '' }}">
                                <label>Nội Dung <i>(bắt buộc)</i></label>
                                @if($errors->has('content'))
                                    <span class="help-block">{{ $errors->first('content') }}</span>
                                @endif
                                <textarea class="form-control TextEditorInput" name="content">{{ old('content', ($type == \App\Models\CourseItem::TYPE_TEXT_DB ? $courseItem->content : '')) }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 CourseItemVideoTypeInput" style="{{ $type != \App\Models\CourseItem::TYPE_VIDEO_DB ? 'display: none' : '' }}">
                            <div class="form-group{{ $errors->has('video_path') ? ' has-error': '' }}">
                                <label>Đường Dẫn Video<i>(bắt buộc)</i></label>
                                <input type="text" class="form-control" name="video_path" value="{{ old('video_path', ($type == \App\Models\CourseItem::TYPE_VIDEO_DB ? $courseItem->content : '')) }}" />
                                @if($errors->has('video_path'))
                                    <span class="help-block">{{ $errors->first('video_path') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Tên EN</label>
                                <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $courseItem->name_en) }}" />
                            </div>
                        </div>
                        <div class="col-sm-12 CourseItemTextTypeInput" style="{{ $type != \App\Models\CourseItem::TYPE_TEXT_DB ? 'display: none' : '' }}">
                            <div class="form-group">
                                <label>Nội Dung EN</label>
                                <textarea class="form-control TextEditorInput" name="content_en">{{ old('content_en', ($type == \App\Models\CourseItem::TYPE_TEXT_DB ? $courseItem->content_en : '')) }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 CourseItemVideoTypeInput" style="{{ $type != \App\Models\CourseItem::TYPE_VIDEO_DB ? 'display: none' : '' }}">
                            <div class="form-group">
                                <label>Đường Dẫn Video EN</label>
                                <input type="text" class="form-control" name="video_path_en" value="{{ old('video_path_en', ($type == \App\Models\CourseItem::TYPE_VIDEO_DB ? $courseItem->content_en : '')) }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($courseItem->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCourseItem', ['id' => $courseItem->course_id]) }}" class="btn btn-default">Quay Lại</a>
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
            nonbreaking_force_tab: true,
            convert_urls: false
        });

        $('#TypeSelect').change(function() {
            if($(this).val() == '{{ \App\Models\CourseItem::TYPE_TEXT_DB }}')
            {
                $('.CourseItemVideoTypeInput').each(function() {
                    $(this).hide();
                });

                $('.CourseItemTextTypeInput').each(function() {
                    $(this).show();
                });
            }
            else
            {
                $('.CourseItemTextTypeInput').each(function() {
                    $(this).hide();
                });

                $('.CourseItemVideoTypeInput').each(function() {
                    $(this).show();
                });
            }
        });

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