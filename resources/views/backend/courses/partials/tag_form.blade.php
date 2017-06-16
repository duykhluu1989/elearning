<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($tag->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CourseController@adminTag')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($tag->id))
            <?php
            $isDeletable = $tag->isDeletable();
            ?>
            @if($isDeletable == 0)
                <a href="{{ action('Backend\CourseController@deleteTag', ['id' => $tag->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
            @endif
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $tag->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($tag->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CourseController@adminTag')) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($tag->id) && $isDeletable == 0)
            <a href="{{ action('Backend\CourseController@deleteTag', ['id' => $tag->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
</div>
{{ csrf_field() }}