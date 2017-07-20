@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Trang - ' . $article->name)

@section('section')

    <form action="{{ action('Backend\ArticleController@editArticleStatic', ['id' => $article->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\ArticleController@adminArticleStatic')) }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Ảnh</label>
                            <div>
                                <button type="button" class="btn btn-default" id="ElFinderPopupOpen"><i class="fa fa-image fa-fw"></i></button>
                                <input type="hidden" name="image" value="{{ old('image', $article->image) }}" />
                                @if(old('image', $article->image))
                                    <img src="{{ old('image', $article->image) }}" width="100%" alt="Article Image" />
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                            <label>Tên <i>(bắt buộc)</i></label>
                            <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $article->name) }}" />
                            @if($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('name_en') ? ' has-error': '' }}">
                            <label>Tên EN</label>
                            <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $article->name_en) }}" />
                            @if($errors->has('name_en'))
                                <span class="help-block">{{ $errors->first('name_en') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <?php
                            $status = old('status', $article->status);
                            ?>
                            <div>
                                @foreach(\App\Models\Course::getCourseStatus() as $value => $label)
                                    <?php
                                    if($value == \App\Models\Course::STATUS_PUBLISH_DB)
                                        $optionClass = 'label label-success';
                                    else if($value == \App\Models\Course::STATUS_FINISH_DB)
                                        $optionClass = 'label label-primary';
                                    else
                                        $optionClass = 'label label-danger';
                                    ?>
                                    @if($status == $value)
                                        <label class="radio-inline">
                                            <input type="radio" name="status" checked="checked" value="{{ $value }}"><span class="{{ $optionClass }}">{{ $label }}</span>
                                        </label>
                                    @else
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="{{ $value }}"><span class="{{ $optionClass }}">{{ $label }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
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
                                        <input type="text" class="form-control" name="slug" value="{{ old('slug', $article->slug) }}" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Mô Tả Ngắn</label>
                                        <input type="text" class="form-control" name="short_description" value="{{ old('short_description', $article->short_description) }}" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group{{ $errors->has('content') ? ' has-error': '' }}">
                                        <label>Nội Dung <i>(bắt buộc)</i></label>
                                        @if($errors->has('content'))
                                            <span class="help-block">{{ $errors->first('content') }}</span>
                                        @endif
                                        <textarea class="form-control TextEditorInput" name="content">{{ old('content', $article->content) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Liên Kết Tĩnh EN</label>
                                        <input type="text" class="form-control" name="slug_en" value="{{ old('slug_en', $article->slug_en) }}" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Mô Tả Ngắn EN</label>
                                        <input type="text" class="form-control" name="short_description_en" value="{{ old('short_description_en', $article->short_description_en) }}" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Nội Dung EN</label>
                                        <textarea class="form-control TextEditorInput" name="content_en">{{ old('content_en', $article->content_en) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\ArticleController@adminArticleStatic')) }}" class="btn btn-default">Quay Lại</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/colorbox.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.colorbox-min.js') }}"></script>
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
    </script>
@endpush