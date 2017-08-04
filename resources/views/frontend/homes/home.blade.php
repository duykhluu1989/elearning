@extends('frontend.layouts.main')

@section('page_heading', trans('theme.home'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    <section class="banner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 pad0 text-center">
                    <div class="owl_banner owl-carousel owl-theme">
                        <?php
                        $sliderItems = array();

                        if(isset($widgets[\App\Models\Widget::HOME_SLIDER]))
                        {
                            if(!empty($widgets[\App\Models\Widget::HOME_SLIDER]->detail))
                                $sliderItems = json_decode($widgets[\App\Models\Widget::HOME_SLIDER]->detail, true);
                        }
                        ?>

                        @foreach($sliderItems as $sliderItem)
                            <div class="item">
                                <img src="{{ isset($sliderItem['image']) ? $sliderItem['image'] : '' }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'title') }}" class="img-responsive">
                                <div class="banner_text_content hidden-sm hidden-xs">
                                    <h1>{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'title') }}</h1>
                                    <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'description') }}</p>
                                    <a href="{{ isset($sliderItem['url']) ? $sliderItem['url'] : 'javascript:void(0)' }}" class="btn btn_yellow">@lang('theme.view_more')</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main>
        <section>
            <div class="container">
                <div class="row">
                    <h3 class="title_line">@lang('theme.free_course')</h3>
                    <div class="col-lg-12">
                        <div class="owl_khmp owl-carousel owl-theme">

                            @foreach($groupCourses[\App\Models\Widget::GROUP_FREE_COURSE] as $course)
                                <div class="item">
                                    <div class="box_item">
                                        <div class="border"></div>
                                        <a href="javascript:void(0)"><img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}" class="img-responsive"></a>
                                        <div class="ticker">
                                            <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->view_count) }}</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->bought_count) }}</span></p>
                                        </div>
                                        <div class="box_item_content">
                                            <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}</p>
                                            <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course->category, 'name') }}</p>
                                            <p class="gia">
                                                @if($course->validatePromotionPrice())
                                                    {{ \App\Libraries\Helpers\Utility::formatNumber($course->promotionPrice->price) . 'đ' }}
                                                @else
                                                    {{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . 'đ' }}
                                                @endif
                                            </p>
                                            <a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}" class="btn btn_yellow btnMua">@lang('theme.buy')</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-lg-12 text-center mt30">
                        <a href="{{ action('Frontend\CourseController@adminCourse', ['sort' => 'free']) }}" class="btn btnShowall">@lang('theme.view_more') <i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg_gray">
            <div class="container">
                <div class="row">
                    <h3 class="title_line">@lang('theme.discount_course')</h3>
                    <div class="col-lg-12">
                        <div class="owl_khmp owl-carousel owl-theme">

                            @foreach($groupCourses[\App\Models\Widget::GROUP_DISCOUNT_COURSE] as $course)
                                <div class="item">
                                    <div class="box_item">
                                        <div class="border"></div>
                                        <a href="javascript:void(0)"><img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}" class="img-responsive"></a>
                                        <div class="ticker">
                                            <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->view_count) }}</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->bought_count) }}</span></p>
                                        </div>
                                        <div class="box_item_content">
                                            <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}</p>
                                            <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course->category, 'name') }}</p>
                                            <p class="gia">
                                                @if($course->validatePromotionPrice())
                                                    {{ \App\Libraries\Helpers\Utility::formatNumber($course->promotionPrice->price) . 'đ' }}
                                                @else
                                                    {{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . 'đ' }}
                                                @endif
                                            </p>
                                            <a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}" class="btn btn_yellow btnMua">@lang('theme.buy')</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-lg-12 text-center mt30">
                        <a href="{{ action('Frontend\CourseController@adminCourse', ['sort' => 'promotion']) }}" class="btn btnShowall">@lang('theme.view_more') <i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="">
            <div class="container">
                <div class="row">
                    <h3 class="title_line">@lang('theme.highlight_course')</h3>
                    <div class="col-lg-12">
                        <div class="owl_khmp owl-carousel owl-theme">

                            @foreach($groupCourses[\App\Models\Widget::GROUP_HIGHLIGHT_COURSE] as $course)
                                <div class="item">
                                    <div class="box_item">
                                        <div class="border"></div>
                                        <a href="javascript:void(0)"><img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}" class="img-responsive"></a>
                                        <div class="ticker">
                                            <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->view_count) }}</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->bought_count) }}</span></p>
                                        </div>
                                        <div class="box_item_content">
                                            <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}</p>
                                            <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course->category, 'name') }}</p>
                                            <p class="gia">
                                                @if($course->validatePromotionPrice())
                                                    {{ \App\Libraries\Helpers\Utility::formatNumber($course->promotionPrice->price) . 'đ' }}
                                                @else
                                                    {{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . 'đ' }}
                                                @endif
                                            </p>
                                            <a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}" class="btn btn_yellow btnMua">@lang('theme.buy')</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-lg-12 text-center mt30">
                        <a href="{{ action('Frontend\CourseController@adminCourse', ['sort' => 'highlight']) }}" class="btn btnShowall">@lang('theme.view_more') <i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="banner_qc">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        $adHorizontalTopItem = null;

                        if(isset($widgets[\App\Models\Widget::ADVERTISE_HORIZONTAL_TOP]))
                        {
                            if(!empty($widgets[\App\Models\Widget::ADVERTISE_HORIZONTAL_TOP]->detail))
                            {
                                $adHorizontalTopItems = json_decode($widgets[\App\Models\Widget::ADVERTISE_HORIZONTAL_TOP]->detail, true);

                                $randomKey = array_rand($adHorizontalTopItems);

                                $adHorizontalTopItem = $adHorizontalTopItems[$randomKey];
                            }
                        }
                        ?>
                        @if($adHorizontalTopItem)
                            @if(isset($adHorizontalTopItem['script']))
                                <?php echo $adHorizontalTopItem['script']; ?>
                            @else
                                <a href="{{ isset($adHorizontalTopItem['url']) ? $adHorizontalTopItem['url'] : 'javascript:void(0)' }}">
                                    <img src="{{ isset($adHorizontalTopItem['image']) ? $adHorizontalTopItem['image'] : '' }}" alt="Advertiser" class="img-responsive">
                                </a>
                            @endif
                        @else
                            <a href="javascript:void(0)"><img src="{{ asset('themes/images/qc_ngang.jpg') }}" alt="Advertiser" class="img-responsive"></a>
                        @endif

                    </div>
                </div>
            </div>
        </section>

        <section class="chuyengia bg_gray">
            <div class="container">
                <div class="qc_left hidden-sm hidden-xs">
                    <?php
                    $adVerticalLeftItem = null;

                    if(isset($widgets[\App\Models\Widget::ADVERTISE_VERTICAL_LEFT]))
                    {
                        if(!empty($widgets[\App\Models\Widget::ADVERTISE_VERTICAL_LEFT]->detail))
                        {
                            $adVerticalLeftItems = json_decode($widgets[\App\Models\Widget::ADVERTISE_VERTICAL_LEFT]->detail, true);

                            $randomKey = array_rand($adVerticalLeftItems);

                            $adVerticalLeftItem = $adVerticalLeftItems[$randomKey];
                        }
                    }
                    ?>

                    @if($adVerticalLeftItem)
                        @if(isset($adVerticalLeftItem['script']))
                            <?php echo $adVerticalLeftItem['script']; ?>
                        @else
                            <a href="{{ isset($adVerticalLeftItem['url']) ? $adVerticalLeftItem['url'] : 'javascript:void(0)' }}"><img src="{{ isset($adVerticalLeftItem['image']) ? $adVerticalLeftItem['image'] : '' }}" alt="Advertiser" class="img-responsive"></a>
                        @endif
                        <a href="javascript:void(0)" class="btnClose"></a>
                    @else
                        <a href="javascript:void(0)"><img src="{{ asset('themes/images/qc.jpg') }}" alt="Advertiser" class="img-responsive"></a>
                        <a href="javascript:void(0)" class="btnClose"></a>
                    @endif
                </div>
                <div class="qc_right hidden-xs hidden-sm">
                    <?php
                    $adVerticalRightItem = null;

                    if(isset($widgets[\App\Models\Widget::ADVERTISE_VERTICAL_RIGHT]))
                    {
                        if(!empty($widgets[\App\Models\Widget::ADVERTISE_VERTICAL_RIGHT]->detail))
                        {
                            $adVerticalRightItems = json_decode($widgets[\App\Models\Widget::ADVERTISE_VERTICAL_RIGHT]->detail, true);

                            $randomKey = array_rand($adVerticalRightItems);

                            $adVerticalRightItem = $adVerticalRightItems[$randomKey];
                        }
                    }
                    ?>

                    @if($adVerticalRightItem)
                        @if(isset($adVerticalRightItem['script']))
                            <?php echo $adVerticalRightItem['script']; ?>
                        @else
                            <a href="{{ isset($adVerticalRightItem['url']) ? $adVerticalRightItem['url'] : 'javascript:void(0)' }}"><img src="{{ isset($adVerticalRightItem['image']) ? $adVerticalRightItem['image'] : '' }}" alt="Advertiser" class="img-responsive"></a>
                        @endif
                        <a href="javascript:void(0)" class="btnClose"></a>
                    @else
                        <a href="javascript:void(0)"><img src="{{ asset('themes/images/qc.jpg') }}" alt="Advertiser" class="img-responsive"></a>
                        <a href="javascript:void(0)" class="btnClose"></a>
                    @endif
                </div>
                <div class="row">
                    <h3 class="title_line">@lang('theme.expert')</h3>
                    <div class="col-lg-12">
                        <div class="owl_chuyengia owl-carousel owl-theme">
                            <?php
                            $expertItems = array();

                            if(isset($widgets[\App\Models\Widget::GROUP_STAFF_EXPERT]))
                            {
                                if(!empty($widgets[\App\Models\Widget::GROUP_STAFF_EXPERT]->detail))
                                    $expertItems = json_decode($widgets[\App\Models\Widget::GROUP_STAFF_EXPERT]->detail, true);
                            }
                            ?>

                            @foreach($expertItems as $expertItem)
                                <div class="item">
                                    <p class="name">{{ isset($expertItem['name']) ? $expertItem['name'] : '' }}</p>
                                    <a href="{{ isset($expertItem['url']) ? $expertItem['url'] : 'javascript:void(0)' }}"><img src="{{ isset($expertItem['image']) ? $expertItem['image'] : '' }}" alt="{{ isset($expertItem['name']) ? $expertItem['name'] : '' }}" class="img-responsive"></a>
                                    <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($expertItem, 'quote') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-12 text-center mt30">
                        <a href="{{ action('Frontend\ArticleController@adminExpert') }}" class="btn btnShowall">@lang('theme.all_article') <i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative tructuyen">
            <div class="container">
                <div class="row">
                    <h3 class="title_line">@lang('theme.expert_online')</h3>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h3 class="title_underline"><i class="fa fa-calendar" aria-hidden="true"></i> @lang('theme.old_event')</h3>
                        <ul>

                            @foreach($oldExpertEvents as $oldExpertEvent)
                                <li><a href="{{ $oldExpertEvent->url }}">✓ {{ \App\Libraries\Helpers\Utility::getValueByLocale($oldExpertEvent, 'name') }}</a></li>
                            @endforeach

                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h3 class="title_underline"><i class="fa fa-circle" aria-hidden="true"></i> @lang('theme.online')</h3>
                        <div class="owl_tructuyen owl-carousel owl-theme">

                            @foreach($onlineExperts as $onlineExpert)
                                <?php
                                $currentEvent = $onlineExpert->getCurrentEvent();
                                ?>
                                <div class="item OnlineExpert"<?php echo !empty($currentEvent) ? (' data-url="' . $currentEvent->url . '"') : ''; ?>>
                                    <a href="javascript:void(0)"><img src="{{ $onlineExpert->user->avatar }}" alt="{{ $onlineExpert->user->profile->name }}" class="img-responsive"></a>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10 col-sm-12 col-xs-12" id="OnlineExpertLiveVideo">
                    </div>
                </div>
            </div>
        </section>

        @for($i = 1;$i <= 3;$i ++)
            <?php
            $details = array();

            if(isset($widgets[\App\Models\Widget::GROUP_CUSTOM_COURSE . '_' . $i]))
            {
                if(!empty($widgets[\App\Models\Widget::GROUP_CUSTOM_COURSE . '_' . $i]->detail))
                    $details = json_decode($widgets[\App\Models\Widget::GROUP_CUSTOM_COURSE . '_' . $i]->detail, true);
            }
            ?>

            <section class="relative">
                <div class="container">
                    <div class="row">
                        <h3 class="title_line">{{ isset($details['custom_detail']) ? \App\Libraries\Helpers\Utility::getValueByLocale($details['custom_detail'], 'title') : '' }}</h3>
                        <div class="col-lg-12">
                            <div class="owl_markerting owl-carousel owl-theme">

                                @foreach($groupCourses[\App\Models\Widget::GROUP_CUSTOM_COURSE . '_' . $i] as $course)
                                    <div class="item">
                                        <div class="box_item">
                                            <div class="border"></div>
                                            <a href="javascript:void(0)"><img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}" class="img-responsive"></a>
                                            <div class="ticker">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->view_count) }}</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->bought_count) }}</span></p>
                                            </div>
                                            <div class="box_item_content">
                                                <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}</p>
                                                <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($course->category, 'name') }}</p>
                                                <p class="gia">
                                                    @if($course->validatePromotionPrice())
                                                        {{ \App\Libraries\Helpers\Utility::formatNumber($course->promotionPrice->price) . 'đ' }}
                                                    @else
                                                        {{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . 'đ' }}
                                                    @endif
                                                </p>
                                                <a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}" class="btn btn_yellow btnMua">@lang('theme.buy')</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="col-lg-12 text-center mt30">
                            <a href="{{ action('Frontend\CourseController@adminCourse') }}" class="btn btnShowall">@lang('theme.all_course') <i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </section>
        @endfor

        <section class="vechungtoi bg_gray">
            <div class="container-fluid">
                <div class="col-lg-6 boxleft_vechungtoi boxmH display_table">
                    <div class="table_content">
                        <h2>TẠI SAO NÊN CHỌN CÂY ĐÈN THẦN?</h2>
                        <ul class="list_taisao">
                            <li><a href="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi.</a></li>
                            <li><a href="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi.</a></li>
                            <li><a href="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi.</a></li>
                            <li><a href="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi.</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 boxright_vechungtoi boxmH">
                    <h2>@lang('theme.student')</h2>
                    <div class="owl_vechungtoi owl-carousel owl-theme">
                        <?php
                        $studentItems = array();

                        if(isset($widgets[\App\Models\Widget::GROUP_STAFF_STUDENT]))
                        {
                            if(!empty($widgets[\App\Models\Widget::GROUP_STAFF_STUDENT]->detail))
                                $studentItems = json_decode($widgets[\App\Models\Widget::GROUP_STAFF_STUDENT]->detail, true);
                        }
                        ?>

                        @foreach($studentItems as $studentItem)
                            <div class="item">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <a href="{{ isset($studentItem['url']) ? $studentItem['url'] : 'javascript:void(0)' }}">
                                            <img src="{{ isset($studentItem['image']) ? $studentItem['image'] : '' }}" alt="{{ isset($studentItem['name']) ? $studentItem['name'] : '' }}" class="img-responsive">
                                        </a>
                                    </div>
                                    <div class="col-lg-8">
                                        <h4>{{ isset($studentItem['name']) ? $studentItem['name'] : '' }}</h4>
                                        <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($studentItem, 'quote') }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($studentItem, 'description') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="giaovien">
            <div class="container">
                <h3 class="title_line">@lang('theme.teacher_staff')</h3>
                <p class="text-center mb60"> Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="owl_giaovien owl-carousel owl-theme">
                            <?php
                            $teacherItems = array();

                            if(isset($widgets[\App\Models\Widget::GROUP_STAFF_TEACHER]))
                            {
                                if(!empty($widgets[\App\Models\Widget::GROUP_STAFF_TEACHER]->detail))
                                    $teacherItems = json_decode($widgets[\App\Models\Widget::GROUP_STAFF_TEACHER]->detail, true);
                            }
                            ?>

                            @foreach($teacherItems as $teacherItem)
                                <div class="item">
                                    <div class="box_item_gv">
                                        <a href="{{ isset($teacherItem['url']) ? $teacherItem['url'] : 'javascript:void(0)' }}">
                                            <img src="{{ isset($teacherItem['image']) ? $teacherItem['image'] : '' }}" alt="{{ isset($teacherItem['name']) ? $teacherItem['name'] : '' }}" class="img-responsive">
                                            <p class="box_item_content_gv">{{ \App\Libraries\Helpers\Utility::getValueByLocale($teacherItem, 'quote') }}</p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="dmkh bg_gray">
            <div class="container">
                <h3 class="title_line">@lang('theme.category_list')</h3>
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <ul class="list_khoahoc">
                            @foreach($rootCategories as $rootCategory)
                                <li><a href="{{ action('Frontend\CourseController@detailCategory', ['id' => $rootCategory->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($rootCategory, 'slug')]) }}">✓ {{ \App\Libraries\Helpers\Utility::getValueByLocale($rootCategory, 'name') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center mt30">
                        <a href="{{ action('Frontend\CourseController@adminCourse') }}" class="btn btnShowall">@lang('theme.all_course') <i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="tuvanphapluat">
            <div class="container-fluid">
                <h2 class="title_line">@lang('theme.case_advice')</h2>
                <div class="col-lg-6 boxright_vechungtoi boxmH display_table">
                    <div class="table_content">
                        <img src="{{ asset('themes/images/i_money.png') }}" alt="Economy" class="img-responsive">
                        <ul class="list_tuvankt">
                            @foreach($caseAdviceEconomies as $caseAdviceEconomy)
                                <li><a href="{{ action('Frontend\CaseAdviceController@detailCaseAdvice', ['id' => $caseAdviceEconomy->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($caseAdviceEconomy, 'slug')]) }}">✓ {{ \App\Libraries\Helpers\Utility::getValueByLocale($caseAdviceEconomy, 'name') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 boxleft_vechungtoi boxmH display_table">
                    <div class="table_content">
                        <img src="{{ asset('themes/images/i_balance.png') }}" alt="Law" class="img-responsive">
                        <ul class="list_tuvanpl">
                            @foreach($caseAdviceLaws as $caseAdviceLaw)
                                <li><a href="{{ action('Frontend\CaseAdviceController@detailCaseAdvice', ['id' => $caseAdviceLaw->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($caseAdviceLaw, 'slug')]) }}">✓ {{ \App\Libraries\Helpers\Utility::getValueByLocale($caseAdviceLaw, 'name') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center mt30">
                        <a href="{{ action('Frontend\CaseAdviceController@adminCaseAdvice') }}" class="btn btnShowall">@lang('theme.view_more') <i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="tintucnoibat">
            <div class="container">
                <h2 class="title_line">@lang('theme.highlight_news')</h2>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="owl_tintucnoibat owl-carousel owl-theme">

                            @foreach($newsCategories as $newsCategory)
                                <div class="item">
                                    <a href="{{ action('Frontend\NewsController@adminCategory') }}">
                                        <span class="table_content">
                                            <img src="{{ $newsCategory->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($newsCategory, 'name') }}" class="img-responsive">
                                        </span>
                                    </a>
                                    <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($newsCategory, 'name') }}</p>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="capchungchikh bg_gray">
            <div class="container">
                <h3 class="title_line">@lang('theme.certificate')</h3>
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <ul class="list_khoahoc">
                            @foreach($certificates as $certificate)
                                <li><a href="javascript:void(0)" class="CertificateSignUp" data-certificate-id="{{ $certificate->id }}">✓ {{ \App\Libraries\Helpers\Utility::getValueByLocale($certificate, 'name') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center mt30">
                        <a href="{{ action('Frontend\CertificateController@adminCertificate') }}" class="btn btnShowall">@lang('theme.all_certificate') <i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div id="CertificateSignUpModal" class="modal fade modal_general" data-backdrop="static" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">@lang('theme.sign_up') <span class="logo_small"><img src="{{ asset('themes/images/logo_small.png') }}" alt="Logo" class="img-responsive"></span></h4>
                </div>
                <div class="modal-body">
                    <form action="{{ action('Frontend\CertificateController@registerCertificate') }}" method="POST" role="form" class="frm_dangky" id="CertificateSignUpForm">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="* @lang('theme.name')" required="required" />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="phone" placeholder="* @lang('theme.phone')" required="required" />
                        </div>
                        <input type="hidden" name="certificate_id" />
                        <button type="submit" class="btn btn-block btnDangky">@lang('theme.sign_up')</button>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script type="text/javascript">
        $('.OnlineExpert').click(function() {
            if($(this).attr('data-url'))
            {
                $('#OnlineExpertLiveVideo').html('' +
                    '<div class="fb-video" data-href="' + $(this).attr('data-url') + '" data-width="auto" data-allowfullscreen="true" data-show-text="true" data-autoplay="true" data-show-captions="true">' +
                    '<blockquote cite="' + $(this).attr('data-url') + '" class="fb-xfbml-parse-ignore">' +
                    '</blockquote>' +
                    '</div>' +
                '');

                try
                {
                    FB.XFBML.parse();
                }
                catch(exception)
                {

                }
            }
        });

        $('.CertificateSignUp').click(function() {
            $('input[name="certificate_id"]').val($(this).data('certificate-id'));
            $('#CertificateSignUpModal').modal('show');
        });

        $('#CertificateSignUpForm').submit(function(e) {
            e.preventDefault();

            var formElem = $(this);

            formElem.find('input').each(function() {
                $(this).parent().removeClass('has-error').find('span[class="help-block"]').first().remove();
            });

            $.ajax({
                url: '{{ action('Frontend\CertificateController@registerCertificate') }}',
                type: 'post',
                data: '_token=' + $('input[name="_token"]').first().val() + '&' + formElem.serialize(),
                success: function(result) {
                    if(result)
                    {
                        if(result == 'Success')
                        {
                            $('#CertificateSignUpModal').modal('hide');

                            formElem.find('input').each(function() {
                                $(this).val('');
                            });

                            swal({
                                title: '@lang('theme.sign_up_success')',
                                type: 'success',
                                confirmButtonClass: 'btn-success'
                            });
                        }
                        else
                        {
                            result = JSON.parse(result);

                            for(var name in result)
                            {
                                if(result.hasOwnProperty(name))
                                {
                                    formElem.find('input[name="' + name + '"]').first().parent().addClass('has-error').append('' +
                                        '<span class="help-block">' + result[name][0] + '</span>' +
                                    '');
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush