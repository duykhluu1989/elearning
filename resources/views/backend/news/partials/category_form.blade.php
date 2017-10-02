<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($category->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\NewsController@adminCategory')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($category->id))
            <a href="{{ action('Backend\NewsController@deleteCategory', ['id' => $category->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Ảnh</label>
                    <div>
                        <button type="button" class="btn btn-default" id="ElFinderPopupOpen"><i class="fa fa-image fa-fw"></i></button>
                        <input type="hidden" name="image" value="{{ old('image', $category->image) }}" />
                        @if(old('image', $category->image))
                            <img src="{{ old('image', $category->image) }}" width="100%" alt="News Image" />
                        @endif
                    </div>
                </div>
            </div>
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
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Trạng Thái</label>
                    <?php
                    $status = old('status', $category->status);
                    ?>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="status" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($status == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
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
                    <div class="input-group">
                        @if(empty($category->id))
                            <span class="input-group-addon">{{ url('newsCategory') }}/</span>
                        @else
                            <span class="input-group-addon">{{ url('newsCategory', ['id' => $category->id]) }}/</span>
                        @endif
                        <input type="text" class="form-control" name="slug" value="{{ old('slug', $category->slug) }}" />
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Liên Kết Tĩnh EN</label>
                    <div class="input-group">
                        @if(empty($category->id))
                            <span class="input-group-addon">{{ url('newsCategory') }}/</span>
                        @else
                            <span class="input-group-addon">{{ url('newsCategory', ['id' => $category->id]) }}/</span>
                        @endif
                        <input type="text" class="form-control" name="slug_en" value="{{ old('slug_en', $category->slug_en) }}" />
                    </div>
                </div>
            </div>
            <?php
            $details = array();

            if(!empty($category->rss))
                $details = json_decode($category->rss, true);
            ?>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Rss</label>
                    <div class="no-padding">
                        <table class="table table-bordered table-striped table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Đường Dẫn</th>
                                <th class="col-sm-1 text-center">
                                    <button type="button" class="btn btn-primary" id="NewRssItemButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Thêm Mới"><i class="fa fa-plus fa-fw"></i></button>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="ListRssItem">
                            @foreach($details as $detail)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="detail[rss][]" value="{{ isset($detail['rss']) ? $detail['rss'] : '' }}" required="required" />
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-default RemoveRssItemButton"><i class="fa fa-trash-o fa-fw"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($category->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\NewsController@adminCategory')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($category->id))
            <a href="{{ action('Backend\NewsController@deleteCategory', ['id' => $category->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
</div>
{{ csrf_field() }}

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/colorbox.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.colorbox-min.js') }}"></script>
    <script type="text/javascript">
        $('#NewRssItemButton').click(function() {
            $('#ListRssItem').append('' +
                '<tr>' +
                '<td>' +
                '<input type="text" class="form-control" name="detail[rss][]" required="required" />' +
                '</td>' +
                '<td class="text-center">' +
                '<button type="button" class="btn btn-default RemoveRssItemButton"><i class="fa fa-trash-o fa-fw"></i></button>' +
                '</td>' +
            '</tr>');
        });

        $('#ListRssItem').on('click', 'button', function() {
            if($(this).hasClass('RemoveRssItemButton'))
                $(this).parent().parent().remove();
        });

        var elFinderSelectedFile;

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
                    '<img src="' + fileUrl + '" width="100%" alt="News Image" />' +
                '');
            }
        }
    </script>
@endpush