<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($event->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\ExpertController@adminExpertEvent', ['id' => $event->expert_id])) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($event->id))
            <a href="{{ action('Backend\ExpertController@deleteExpertEvent', ['id' => $event->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $event->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Tên EN</label>
                    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $event->name_en) }}" />
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('url') ? ' has-error': '' }}">
                    <label>Url <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="url" required="required" value="{{ old('url', $event->url) }}" />
                    @if($errors->has('url'))
                        <span class="help-block">{{ $errors->first('url') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($event->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\ExpertController@adminExpertEvent', ['id' => $event->expert_id])) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($event->id))
            <a href="{{ action('Backend\ExpertController@deleteExpertEvent', ['id' => $event->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
</div>
{{ csrf_field() }}