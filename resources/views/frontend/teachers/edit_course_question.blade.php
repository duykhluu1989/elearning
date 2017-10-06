@extends('frontend.layouts.main')

@section('page_heading', trans('theme.teacher_label'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.teachers.partials.course_question_breadcrumb')

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <div class="row">
                                <h4>{{ \App\Libraries\Helpers\Utility::getValueByLocale($question->course, 'name') }}</h4>
                                <form action="{{ action('Frontend\TeacherController@editCourseQuestion', ['id' => $question->id]) }}" method="POST">
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-3">@lang('theme.questions')</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" readonly="readonly">{{ $question->question }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-3">@lang('theme.answer')</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="answer">{{ old('answer', $question->answer) }}</textarea>
                                        </div>
                                        @if($errors->has('answer'))
                                            <div class="form-group has-error">
                                                <span class="help-block">* {{ $errors->first('answer') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-3">@lang('theme.status')</label>
                                        <div class="col-sm-4">
                                            <?php
                                            $status = old('status', $question->status);
                                            ?>
                                            <select class="form-control" name="status">
                                                <option <?php echo ($status == \App\Models\CourseQuestion::STATUS_WAIT_TEACHER_DB ? 'selected="selected" ' : ''); ?>value="{{ \App\Models\CourseQuestion::STATUS_WAIT_TEACHER_DB }}">@lang('theme.need_answer')</option>
                                                <option <?php echo ($status == \App\Models\CourseQuestion::STATUS_ACTIVE_DB ? 'selected="selected" ' : ''); ?>value="{{ \App\Models\CourseQuestion::STATUS_ACTIVE_DB }}">@lang('theme.answered')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-lg btnRed"><i class="fa fa-floppy-o" aria-hidden="true"></i> @lang('theme.save')</button>
                                    </div>
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop