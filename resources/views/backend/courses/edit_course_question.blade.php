@extends('backend.layouts.main')

@section('page_heading', $question->course->name . ' - Trả Lời Câu Hỏi')

@section('section')

    <form action="{{ action('Backend\CourseController@editCourseQuestion', ['id' => $question->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CourseController@adminCourseQuestion')) }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Học Viên</label>
                            <span class="form-control no-border">{{ $question->user->profile->name }}</span>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Khóa Học</label>
                            <span class="form-control no-border">{{ $question->course->name }}</span>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Câu Hỏi</label>
                            <span class="form-control no-border">{{ $question->question }}</span>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has('answer') ? ' has-error': '' }}">
                            <label>Trả Lời</label>
                            <textarea class="form-control" name="answer">{{ old('answer', $question->answer) }}</textarea>
                            @if($errors->has('answer'))
                                <span class="help-block">{{ $errors->first('answer') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <?php
                            $status = old('status', $question->status);
                            ?>
                            <select class="form-control" name="status">
                                @foreach(\App\Models\CourseQuestion::getCourseQuestionStatus() as $value => $label)
                                    @if($status == $value)
                                        <option selected="selected" value="{{ $value }}">{{ $label }}</option>
                                    @else
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Thời Gian</label>
                            <span class="form-control no-border">{{ $question->created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\CourseController@adminCourseQuestion')) }}" class="btn btn-default">Quay Lại</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop