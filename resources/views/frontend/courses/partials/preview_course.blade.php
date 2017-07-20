@if(isset($course))
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-center">{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-5 display_table boxmH">
                <div class="table_content">
                    <img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}" class="img-responsive">
                </div>
            </div>
            <div class="col-lg-7 boxmH">
                <div class="box_info_khoahoc">
                    <p class="big_price"><i class="fa fa-tags" aria-hidden="true"></i>
                        @if($course->validatePromotionPrice())
                            <span class="new_price">{{ \App\Libraries\Helpers\Utility::formatNumber($course->promotionPrice->price) . 'đ' }}</span> - <span class="sale">({{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . 'đ' }})</span> <span class="sale_percent">-{{ round(($course->price - $course->promotionPrice->price) * 100 / $course->price) }}%</span>
                        @else
                            <span class="new_price">{{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . 'đ' }}</span>
                        @endif
                    </p>
                    <div class="row">
                        <div class="col-lg-8">

                            @if($bought == false)
                                <a href="#" class="btn btn-lg btnMuaKH"><i class="fa fa-cart-plus" aria-hidden="true"></i>@lang('theme.buy_course')</a>
                                <a href="#" class="btn btn-lg btnThemGH"><i class="fa fa-plus-square-o" aria-hidden="true"></i>@lang('theme.add_cart')</a>
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

                            <div class="fb-like" data-href="{{ action('Frontend\CourseController@detailCourse', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="true"></div>
                        </div>
                        <div class="col-lg-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-center"></h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-5 display_table boxmH">
                <div class="table_content">
                </div>
            </div>
            <div class="col-lg-7 boxmH">
                <div class="box_info_khoahoc">
                    <p class="big_price"><i class="fa fa-tags" aria-hidden="true"></i>
                    </p>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="box_sl_baigiang">
                            </div>
                        </div>
                        <div class="col-lg-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif