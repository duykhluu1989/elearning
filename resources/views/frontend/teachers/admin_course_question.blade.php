@extends('frontend.layouts.main')

@section('page_heading', trans('theme.teacher_label'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.teacher_label')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.teacher_label')</h5>

                            @include('frontend.teachers.partials.navigation')

                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <div class="row table-responsive">
                                <h4>@lang('theme.all_course')</h4>
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>@lang('theme.course')</th>
                                        <th>@lang('theme.name')</th>
                                        <th>@lang('theme.question')</th>
                                        <th>@lang('theme.answer')</th>
                                        <th>@lang('theme.status')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <form action="{{ action('Frontend\TeacherController@adminCourseQuestion') }}" method="get">
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control" name="course" placeholder="@lang('theme.search_course')" value="{{ request()->input('course') }}" />
                                            </td>
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control" name="question" placeholder="@lang('theme.search_question')" value="{{ request()->input('question') }}" />
                                            </td>
                                            <td></td>
                                            <td>
                                                <?php
                                                $questionStatus = (request()->input('status') === null ? null : (int)request()->input('status'));
                                                ?>
                                                <select class="form-control" name="status">
                                                    <option>@lang('theme.search_status')</option>
                                                    <option <?php echo ($questionStatus === \App\Models\CourseQuestion::STATUS_WAIT_TEACHER_DB ? 'selected="selected" ' : ''); ?>value="{{ \App\Models\CourseQuestion::STATUS_WAIT_TEACHER_DB }}">@lang('theme.need_answer')</option>
                                                    <option <?php echo ($questionStatus === \App\Models\CourseQuestion::STATUS_ACTIVE_DB ? 'selected="selected" ' : ''); ?>value="{{ \App\Models\CourseQuestion::STATUS_ACTIVE_DB }}">@lang('theme.answered')</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button type="submit" class="hidden"></button>
                                            </td>
                                        </tr>
                                    </form>

                                    @foreach($courseQuestions as $courseQuestion)
                                        <tr>
                                            <td class="col-sm-2">
                                                <a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $courseQuestion->course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($courseQuestion->course, 'slug')]) }}">
                                                    <img src="{{ $courseQuestion->course->image }}" width="100%" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($courseQuestion->course, 'name') }}" />
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $courseQuestion->course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($courseQuestion->course, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($courseQuestion->course, 'name') }}</a>
                                            </td>
                                            <td>{{ $courseQuestion->user->profile->name }}</td>
                                            <td>{{ $courseQuestion->question }}</td>
                                            <td>{{ $courseQuestion->answer }}</td>
                                            <td>
                                                @if($courseQuestion->question == \App\Models\CourseQuestion::STATUS_WAIT_TEACHER_DB)
                                                    @lang('theme.need_answer')
                                                @else
                                                    @lang('theme.answered')
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ action('Frontend\TeacherController@editCourseQuestion', ['id' => $courseQuestion->id]) }}" class="btn btn-sm btnThemGH">@lang('theme.answer')</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <ul class="pagination">
                                        @if($courseQuestions->lastPage() > 1)
                                            @if($courseQuestions->currentPage() > 1)
                                                <li><a href="{{ $courseQuestions->previousPageUrl() }}">&laquo;</a></li>
                                                <li><a href="{{ $courseQuestions->url(1) }}">1</a></li>
                                            @endif

                                            @for($i = 2;$i >= 1;$i --)
                                                @if($courseQuestions->currentPage() - $i > 1)
                                                    @if($courseQuestions->currentPage() - $i > 2 && $i == 2)
                                                        <li>...</li>
                                                        <li><a href="{{ $courseQuestions->url($courseQuestions->currentPage() - $i) }}">{{ $courseQuestions->currentPage() - $i }}</a></li>
                                                    @else
                                                        <li><a href="{{ $courseQuestions->url($courseQuestions->currentPage() - $i) }}">{{ $courseQuestions->currentPage() - $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            <li class="active"><a href="javascript:void(0)">{{ $courseQuestions->currentPage() }}</a></li>

                                            @for($i = 1;$i <= 2;$i ++)
                                                @if($courseQuestions->currentPage() + $i < $courseQuestions->lastPage())
                                                    @if($courseQuestions->currentPage() + $i < $courseQuestions->lastPage() - 1 && $i == 2)
                                                        <li><a href="{{ $courseQuestions->url($courseQuestions->currentPage() + $i) }}">{{ $courseQuestions->currentPage() + $i }}</a></li>
                                                        <li>...</li>
                                                    @else
                                                        <li><a href="{{ $courseQuestions->url($courseQuestions->currentPage() + $i) }}">{{ $courseQuestions->currentPage() + $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            @if($courseQuestions->currentPage() < $courseQuestions->lastPage())
                                                <li><a href="{{ $courseQuestions->url($courseQuestions->lastPage()) }}">{{ $courseQuestions->lastPage() }}</a></li>
                                                <li><a href="{{ $courseQuestions->nextPageUrl() }}">&raquo;</a></li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop