@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($course, 'short_description'))

@section('og_image', $course->image)

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

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
                <div class="row">
                    <div class="col-lg-6 boxmH display_table">
                        <div class="table_content">
                            <a href="javascript:void(0)"><img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}" class="img-responsive w100p"></a>
                        </div>
                    </div>
                    <div class="col-lg-6 boxmH">
                        <div class="box_info_khoahoc">
                            @if($bought == false)
                                <p class="big_price"><i class="fa fa-tags" aria-hidden="true"></i>
                                    @if($course->validatePromotionPrice())
                                        <span class="new_price">{{ \App\Libraries\Helpers\Utility::formatNumber($course->promotionPrice->price) . 'đ' }}</span> - <span class="sale">({{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . 'đ' }})</span> <span class="sale_percent">-{{ round(($course->price - $course->promotionPrice->price) * 100 / $course->price) }}%</span>
                                    @else
                                        <span class="new_price">{{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . 'đ' }}</span>
                                    @endif
                                </p>
                            @endif

                            <div class="row">
                                <div class="col-lg-8">

                                    @if($bought == false)
                                        <a href="javascript:void(0)" class="btn btn-lg btnMuaKH QuickBuyCourse" data-course-id="{{ $course->id }}"><i class="fa fa-cart-plus" aria-hidden="true"></i>@lang('theme.buy_course')</a>
                                        <a href="javascript:void(0)" class="btn btn-lg btnThemGH AddCartItem" data-course-id="{{ $course->id }}"><i class="fa fa-plus-square-o" aria-hidden="true"></i>@lang('theme.add_cart')</a>
                                    @endif

                                    <div class="box_sl_baigiang">
                                        <p>@lang('theme.course_item_count'): <span><b>{{ $course->item_count }}</b></span></p>
                                        @if(!empty($course->video_length))
                                            <p>@lang('theme.video_length'): <span><b>{{ \App\Libraries\Helpers\Utility::formatTimeString($course->video_length) }}</b></span></p>
                                        @endif
                                        @if(!empty($course->audio_length))
                                            <p>@lang('theme.audio_length'): <span><b>{{ \App\Libraries\Helpers\Utility::formatTimeString($course->audio_length) }}</b></span></p>
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
                <div class="row mt30">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs tabs_info">
                            <li class="active"><a data-toggle="tab" href="#section_gioithieu">@lang('theme.course_description')</a></li>
                            <li><a data-toggle="tab" href="#section_chitiet">@lang('theme.detail')</a></li>
                            <li><a data-toggle="tab" href="#section_binhluan">Đánh giá & bình luận</a></li>
                        </ul>
                        <div class="tab-content tabs_info_content">
                            <div id="section_gioithieu" class="tab-pane fade in active">
                                <h4>@lang('theme.course_description_title')</h4>
                                <?php echo \App\Libraries\Helpers\Utility::getValueByLocale($course, 'description'); ?>
                            </div>
                            <div id="section_chitiet" class="tab-pane fade">
                                @if($bought == false)
                                    <h4>@lang('theme.list_course_item')</h4>
                                    <table class="table table-bordered table-hover table-condensed">
                                        <tbody>
                                        @foreach($course->courseItems as $courseItem)
                                            <tr>
                                                <td class="text-center">{{ $courseItem->number }}</td>
                                                <td class="col-sm-11">{{ \App\Libraries\Helpers\Utility::getValueByLocale($courseItem, 'name') }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <h4>{{ $userCourse->course_item_tracking . ' / ' . $course->item_count }} @lang('theme.lecture_complete')</h4>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ round($userCourse->course_item_tracking * 100 / $course->item_count) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ round($userCourse->course_item_tracking * 100 / $course->item_count) }}%">
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover table-condensed">
                                        <tbody>
                                        @foreach($course->courseItems as $courseItem)
                                            <tr class="CourseItemNavigation{{ $courseItem->number == $userCourse->course_item_tracking + 1 ? ' info' : '' }}" style="cursor: pointer" data-href="{{ action('Frontend\CourseController@detailCourseItem', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug'), 'number' => $courseItem->number]) }}">
                                                <td class="text-center">{{ $courseItem->number }}</td>
                                                <td class="col-sm-11">
                                                    {{ \App\Libraries\Helpers\Utility::getValueByLocale($courseItem, 'name') }}
                                                </td>
                                                <td class="text-center">
                                                    @if(!empty($courseItem->video_length))
                                                        {{ \App\Libraries\Helpers\Utility::formatTimeString($courseItem->video_length) }}
                                                    @elseif(!empty($courseItem->audio_length))
                                                        {{ \App\Libraries\Helpers\Utility::formatTimeString($courseItem->audio_length) }}
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($courseItem->number <= $userCourse->course_item_tracking)
                                                        <i class="fa fa-check-circle"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div id="section_binhluan" class="tab-pane fade">
                                <h3>Dropdown 1</h3>
                                <p>WInteger convallis, nulla in sollicitudin placerat, ligula enim auctor lectus, in mollis diam dolor at lorem. Sed bibendum nibh sit amet dictum feugiat. Vivamus arcu sem, cursus a feugiat ut, iaculis at erat. Donec vehicula at ligula vitae venenatis. Sed nunc nulla, vehicula non porttitor in, pharetra et dolor. Fusce nec velit velit. Pellentesque consectetur eros.</p>
                            </div>
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
                if(location.href.indexOf('#section_chitiet') !== -1)
                    $('a[href="#section_chitiet"]').trigger('click');
            });

            $('.CourseItemNavigation').click(function() {
                if($(this).data('href') != '')
                    location.href = $(this).data('href');
            });
        </script>
    @endpush
@endif