@extends('frontend.layouts.main')

@section('page_heading', trans('theme.account'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.account')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.account')</h5>

                            @include('frontend.users.partials.navigation')

                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            @if($userCourses->total() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <th>@lang('theme.course')</th>
                                            <th>@lang('theme.category')</th>
                                            <th>@lang('theme.complete')</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($userCourses as $userCourse)
                                            <tr class="CourseNavigation" style="cursor: pointer" data-href="{{ action('Frontend\CourseController@detailCourse', ['id' => $userCourse->course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($userCourse->course, 'slug')]) }}">
                                                <td>{{ \App\Libraries\Helpers\Utility::getValueByLocale($userCourse->course, 'name') }}</td>
                                                <td>{{ \App\Libraries\Helpers\Utility::getValueByLocale($userCourse->course->category, 'name') }}</td>
                                                <td>{{ $userCourse->course_item_tracking . ' / ' . $userCourse->course->item_count }} @lang('theme.lecture_complete')</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <ul class="pagination">
                                            @if($userCourses->lastPage() > 1)
                                                @if($userCourses->currentPage() > 1)
                                                    <li><a href="{{ $userCourses->previousPageUrl() }}">&laquo;</a></li>
                                                    <li><a href="{{ $userCourses->url(1) }}">1</a></li>
                                                @endif

                                                @for($i = 2;$i >= 1;$i --)
                                                    @if($userCourses->currentPage() - $i > 1)
                                                        @if($userCourses->currentPage() - $i > 2 && $i == 2)
                                                            <li>...</li>
                                                            <li><a href="{{ $userCourses->url($userCourses->currentPage() - $i) }}">{{ $userCourses->currentPage() - $i }}</a></li>
                                                        @else
                                                            <li><a href="{{ $userCourses->url($userCourses->currentPage() - $i) }}">{{ $userCourses->currentPage() - $i }}</a></li>
                                                        @endif
                                                    @endif
                                                @endfor

                                                <li class="active"><a href="javascript:void(0)">{{ $userCourses->currentPage() }}</a></li>

                                                @for($i = 1;$i <= 2;$i ++)
                                                    @if($userCourses->currentPage() + $i < $userCourses->lastPage())
                                                        @if($userCourses->currentPage() + $i < $userCourses->lastPage() - 1 && $i == 2)
                                                            <li><a href="{{ $userCourses->url($userCourses->currentPage() + $i) }}">{{ $userCourses->currentPage() + $i }}</a></li>
                                                            <li>...</li>
                                                        @else
                                                            <li><a href="{{ $userCourses->url($userCourses->currentPage() + $i) }}">{{ $userCourses->currentPage() + $i }}</a></li>
                                                        @endif
                                                    @endif
                                                @endfor

                                                @if($userCourses->currentPage() < $userCourses->lastPage())
                                                    <li><a href="{{ $userCourses->url($userCourses->lastPage()) }}">{{ $userCourses->lastPage() }}</a></li>
                                                    <li><a href="{{ $userCourses->nextPageUrl() }}">&raquo;</a></li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop

@push('scripts')
    <script type="text/javascript">
        $('.CourseNavigation').click(function() {
            if($(this).data('href') != '')
                location.href = $(this).data('href');
        });
    </script>
@endpush