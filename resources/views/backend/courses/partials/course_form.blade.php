<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($course->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCourse') }}" class="btn btn-default">Quay Lại</a>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
                    <label>Tên <i>(bắt buộc)</i></label>
                    <input type="text" class="form-control" name="name" required="required" value="{{ old('name', $course->name) }}" />
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('name_en') ? ' has-error': '' }}">
                    <label>Tên EN</label>
                    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $course->name_en) }}" />
                    @if($errors->has('name_en'))
                        <span class="help-block">{{ $errors->first('name_en') }}</span>
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
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($course->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCourse') }}" class="btn btn-default">Quay lai</a>
    </div>
</div>
{{ csrf_field() }}

@push('scripts')
    <script type="text/javascript">
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
    </script>
@endpush