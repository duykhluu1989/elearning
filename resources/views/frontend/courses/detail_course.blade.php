@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($course, 'short_description'))

@section('og_image', $course->image)

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.courses.partials.course_breadcrumb')

    <main>
        <section class="ct_khoahoc">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}</h2>
                        <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'short_description') }}</p>
                        <div class="ticker2 w135 mb15">
                            <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->view_count) }}</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->bought_count) }}</span></p>
                        </div>
                        <p>@lang('theme.teacher') <span class="red"><b>{{ $course->user->profile->name }}</b></span></p>
                    </div>
                </div>

                @if($bought == false)
                    <div class="row">
                        <div class="col-lg-6 boxmH display_table">
                            <div class="table_content">
                                <a href="javascript:void(0)"><img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}" class="img-responsive w100p"></a>
                            </div>
                        </div>
                        <div class="col-lg-6 boxmH">
                            <div class="box_info_khoahoc">
                                <p class="big_price"><i class="fa fa-tags" aria-hidden="true"></i>
                                    @if($course->validatePromotionPrice())
                                        <?php
                                        $coursePrice = $course->promotionPrice->price;
                                        ?>
                                        <span class="new_price">{{ \App\Libraries\Helpers\Utility::formatNumber($course->promotionPrice->price) . 'đ' }}</span> - <span class="sale">({{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . 'đ' }})</span> <span class="sale_percent">-{{ round(($course->price - $course->promotionPrice->price) * 100 / $course->price) }}%</span>
                                    @else
                                        <?php
                                        $coursePrice = $course->price;
                                        ?>
                                        <span class="new_price">{{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . 'đ' }}</span>
                                    @endif
                                </p>
                                <div class="row">
                                    <div class="col-lg-8">

                                        @if($coursePrice == 0)
                                            <a href="{{ action('Frontend\OrderController@learnCourseNow', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}" class="btn btn-lg btnMuaKH"><i class="fa fa-book" aria-hidden="true"></i> @lang('theme.learn')</a>
                                        @else
                                            <a href="javascript:void(0)" class="btn btn-lg btnMuaKH QuickBuyCourse" data-course-id="{{ $course->id }}"><i class="fa fa-cart-plus" aria-hidden="true"></i> @lang('theme.buy_course')</a>
                                            <a href="javascript:void(0)" class="btn btn-lg btnThemGH AddCartItem" data-course-id="{{ $course->id }}"><i class="fa fa-plus-square-o" aria-hidden="true"></i> @lang('theme.add_cart')</a>
                                        @endif

                                        <div class="box_sl_baigiang">
                                            <p>@lang('theme.course_item_count'): <span><b>{{ $course->item_count }}</b></span></p>
                                            @if(!empty($course->video_length))
                                                <p>@lang('theme.video_length'): <span><b>{{ \App\Libraries\Helpers\Utility::formatTimeString($course->video_length) }}</b></span></p>
                                            @endif
                                            @if(!empty($course->audio_length))
                                                <p>@lang('theme.audio_length'): <span><b>{{ \App\Libraries\Helpers\Utility::formatTimeString($course->audio_length) }}</b></span></p>
                                            @endif
                                            @if(!empty($course->level))
                                                <p>@lang('theme.level'): <span><b>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course->level, 'name') }}</b></span></p>
                                            @endif
                                        </div>

                                        <div class="fb-like" data-href="{{ request()->url() }}" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="true"></div>
                                    </div>
                                    <div class="col-lg-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mt30">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs tabs_info">
                            <li class="{{ $bought == false ? 'active' : '' }}"><a data-toggle="tab" href="#section_gioithieu">@lang('theme.course_description')</a></li>
                            <li class="{{ $bought == true ? 'active' : '' }}"><a data-toggle="tab" href="#section_noidungKH">@lang('theme.detail')</a></li>
                            <li><a data-toggle="tab" href="#section_giangvien">@lang('theme.teacher_label')</a></li>
                            <li><a data-toggle="tab" href="#section_binhluan">@lang('theme.review')</a></li>

                            @if($bought == true)
                                <li><a data-toggle="tab" href="#section_hoidap">@lang('theme.question')</a></li>
                            @endif

                        </ul>
                        <div class="tab-content tabs_info_content">
                            <div id="section_gioithieu" class="tab-pane fade{{ $bought == false ? ' in active' : '' }}">
                                <h4>@lang('theme.course_description_title')</h4>
                                <?php echo \App\Libraries\Helpers\Utility::getValueByLocale($course, 'description'); ?>
                            </div>
                            <div id="section_noidungKH" class="tab-pane fade{{ $bought == true ? ' in active' : '' }}">
                                @if($bought == false)
                                    <h4>@lang('theme.list_course_item')</h4>
                                    <div class="table-responsive table_noidungKH">
                                        <table class="table table-hover table-bordered">
                                            <tbody>

                                            @foreach($course->courseItems as $courseItem)
                                                <tr>
                                                    <td class="text-center">{{ $courseItem->number }}</td>
                                                    <td class="col-sm-11">{{ \App\Libraries\Helpers\Utility::getValueByLocale($courseItem, 'name') }}</td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4>{{ $userCourse->course_item_tracking . ' / ' . $course->item_count }} @lang('theme.lecture_complete')</h4>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ round($userCourse->course_item_tracking * 100 / $course->item_count) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ round($userCourse->course_item_tracking * 100 / $course->item_count) }}%">
                                        </div>
                                    </div>

                                    <div class="table-responsive table_noidungKH">
                                        <table class="table table-hover table-bordered">
                                            <tbody>

                                            @foreach($course->courseItems as $courseItem)
                                                <tr class="CourseItemNavigation{{ $courseItem->number == $userCourse->course_item_tracking + 1 ? ' info' : '' }}" style="cursor: pointer" data-href="{{ action('Frontend\CourseController@detailCourseItem', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug'), 'number' => $courseItem->number]) }}">
                                                    <td class="text-center">{{ $courseItem->number }}</td>
                                                    <td class="col-sm-9" style="border-right: none">
                                                        {{ \App\Libraries\Helpers\Utility::getValueByLocale($courseItem, 'name') }}
                                                    </td>
                                                    <td class="col-sm-2 text-center" style="border-left: none">
                                                        @if(!empty($courseItem->video_length))
                                                            {{ \App\Libraries\Helpers\Utility::formatTimeString($courseItem->video_length) }}
                                                        @elseif(!empty($courseItem->audio_length))
                                                            {{ \App\Libraries\Helpers\Utility::formatTimeString($courseItem->audio_length) }}
                                                        @endif
                                                    </td>
                                                    <td class= text-center">
                                                        @if($courseItem->number <= $userCourse->course_item_tracking)
                                                            <i class="fa fa-check-circle"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                            <div id="section_giangvien" class="tab-pane fade">
                                <h3>{{ $course->user->profile->name }}</h3>
                                <p>{{ $course->user->profile->description }}</p>
                            </div>
                            <div id="section_binhluan" class="tab-pane fade">
                                <h3>@lang('theme.review')</h3>
                                <span id="CourseReviewTab">
                                </span>

                                @if($bought == true)
                                    <hr />
                                    <div class="block_input_comment">
                                        <form id="CourseReviewForm" action="{{ action('Frontend\CourseController@reviewCourse', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}" method="POST" role="form">
                                            <div class="form-group">
                                                <textarea id="CourseReviewDetail" name="detail" class="form-control" rows="8" required="required"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-lg btnGui pull-right">@lang('theme.send')</button>
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                @endif

                            </div>

                            @if($bought == true)
                                <div id="section_hoidap" class="tab-pane fade">
                                    <h3>@lang('theme.question')</h3>
                                    <span id="CourseQuestionTab">
                                    </span>
                                    <hr />
                                    <div class="block_input_comment">
                                        <form id="CourseQuestionForm" action="{{ action('Frontend\CourseController@questionCourse', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}" method="POST" role="form">
                                            <div class="form-group">
                                                <textarea id="CourseQuestionDetail" name="question" class="form-control" rows="8" required="required"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-lg btnGui pull-right">@lang('theme.send')</button>
                                            {{ csrf_field() }}
                                        </form>
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

@if($bought == false)
    @include('frontend.courses.partials.add_cart_item')
@else
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                if(location.href.indexOf('#section_noidungKH') !== -1)
                    $('a[href="#section_noidungKH"]').trigger('click');

                $.ajax({
                    url: '{{ action('Frontend\CourseController@detailCourseQuestion', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}',
                    type: 'get',
                    success: function(result) {
                        if(result)
                            $('#CourseQuestionTab').html(result);
                    }
                });
            });

            $('.CourseItemNavigation').click(function() {
                if($(this).data('href') != '')
                    location.href = $(this).data('href');
            });

            $('#CourseReviewForm').submit(function(e) {
                e.preventDefault();

                var reviewDetailElem = $('#CourseReviewDetail');
                var reviewDetailVal = reviewDetailElem.val().trim();

                if(reviewDetailVal != '')
                {
                    $.ajax({
                        url: '{{ action('Frontend\CourseController@reviewCourse', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}',
                        type: 'post',
                        data: '_token=' + $('input[name="_token"]').first().val() + '&detail=' + reviewDetailVal,
                        success: function(result) {
                            if(result == 'Success')
                            {
                                reviewDetailElem.val('');

                                swal({
                                    title: '@lang('theme.sent')',
                                    type: 'success',
                                    confirmButtonClass: 'btn-success'
                                });
                            }
                        }
                    });
                }
            });

            $('#CourseQuestionTab').on('click', 'a', function() {
                if($(this).attr('data-page'))
                {
                    $.ajax({
                        url: '{{ action('Frontend\CourseController@detailCourseQuestion', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}',
                        type: 'get',
                        data: 'page=' + $(this).attr('data-page'),
                        success: function(result) {
                            if(result)
                                $('#CourseQuestionTab').html(result);
                        }
                    });
                }
            });

            $('#CourseQuestionForm').submit(function(e) {
                e.preventDefault();

                var questionDetailElem = $('#CourseQuestionDetail');
                var questionDetailVal = questionDetailElem.val().trim();

                if(questionDetailVal != '')
                {
                    $.ajax({
                        url: '{{ action('Frontend\CourseController@questionCourse', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}',
                        type: 'post',
                        data: '_token=' + $('input[name="_token"]').first().val() + '&question=' + questionDetailVal,
                        success: function(result) {
                            if(result == 'Success')
                            {
                                questionDetailElem.val('');

                                swal({
                                    title: '@lang('theme.sent')',
                                    type: 'success',
                                    confirmButtonClass: 'btn-success'
                                });
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endif

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajax({
                url: '{{ action('Frontend\CourseController@detailCourseReview', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}',
                type: 'get',
                success: function(result) {
                    if(result)
                        $('#CourseReviewTab').html(result);
                }
            });
        });

        $('#CourseReviewTab').on('click', 'a', function() {
            if($(this).attr('data-page'))
            {
                $.ajax({
                    url: '{{ action('Frontend\CourseController@detailCourseReview', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}',
                    type: 'get',
                    data: 'page=' + $(this).attr('data-page'),
                    success: function(result) {
                        if(result)
                            $('#CourseReviewTab').html(result);
                    }
                });
            }
        });
    </script>
@endpush