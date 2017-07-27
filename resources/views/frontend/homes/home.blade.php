@extends('frontend.layouts.main')

@section('page_heading', trans('theme.home'))

@section('section')

    @include('frontend.layouts.partials.header')

    <section class="hotdeal mt152">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <p><i class="fa fa-fire" aria-hidden="true"></i> Combo khóa học: Tiết kiệm đến 65% học phí khóa học</p>
                </div>
                <div class="col-lg-5">
                    <p class="pull-right"><i class="fa fa-phone-square" aria-hidden="true"></i> CSKH: 0909 000 000 (8h30 - 21h)</p>
                </div>
            </div>
        </div>
    </section>

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

                    @if(count($rootCategories) > 0)
                        <div class="col-lg-12 text-center mt30">
                            <a href="{{ action('Frontend\CourseController@detailCategory', ['id' => $rootCategories[0]->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($rootCategories[0], 'slug')]) }}" class="btn btnShowall">@lang('theme.all_course')<i class="fa fa-angle-double-right fa-lg" aria-hidden="true"></i></a>
                        </div>
                    @endif
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
                </div>
            </div>
        </section>

        <section class="banner_qc">
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
                </div>
            </div>
        </section>

        <section class="relative tructuyen">
            <div class="container">
                <div class="row">
                    <h3 class="title_line">Trực tuyến cùng chuyên gia</h3>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h3 class="title_underline"><i class="fa fa-calendar" aria-hidden="true"></i> Các sự kiện đã diễn ra</h3>
                        <ul>
                            <li><a href="">✓ Lorem ipsum dolor sit amet.</a></li>
                            <li><a href="">✓ Lorem ipsum dolor sit amet, consectetur.</a></li>
                            <li><a href="">✓ Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a></li>
                            <li><a href="">✓ Lorem ipsum dolor sit amet.</a></li>
                            <li><a href="">✓ Lorem ipsum dolor sit.</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h3 class="title_underline"><i class="fa fa-circle" aria-hidden="true"></i> Trực tuyến</h3>
                        <div class="owl_tructuyen owl-carousel owl-theme">
                            <div class="item">
                                <a href="#"><img src="{{ asset('themes/images/cg01.jpg') }}" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item">
                                <a href="#"><img src="{{ asset('themes/images/cg02.jpg') }}" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item">
                                <a href="#"><img src="{{ asset('themes/images/cg03.jpg') }}" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item">
                                <a href="#"><img src="{{ asset('themes/images/cg04.jpg') }}" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item">
                                <a href="#"><img src="{{ asset('themes/images/cg05.jpg') }}" alt="" class="img-responsive"></a>
                            </div>
                            <div class="item">
                                <a href="#"><img src="{{ asset('themes/images/cg01.jpg') }}" alt="" class="img-responsive"></a>
                            </div>
                        </div>
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
                    </div>
                </div>
            </section>
        @endfor

        <section class="vechungtoi bg_gray">
            <div class="container-fluid">
                <div class="col-lg-6 boxleft_vechungtoi boxmH display_table">
                    <div class="table_content">
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-8">
                                <h2>TẠI SAO NÊN CHỌN CÂY ĐÈN THẦN?</h2>
                                <ul class="list_taisao">
                                    <li><a href="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi.</a></li>
                                    <li><a href="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi.</a></li>
                                    <li><a href="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi.</a></li>
                                    <li><a href="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi.</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 boxright_vechungtoi boxmH">
                    <div class="row">
                        <div class="col-lg-8">
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
                        <div class="col-lg-4"></div>
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
            </div>
        </section>

        <section class="tuvanphapluat">
            <div class="container-fluid">
                <h2 class="title_line">@lang('theme.case_advice')</h2>
                <div class="col-lg-6 boxright_vechungtoi boxmH">
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-8">
                            <img src="{{ asset('themes/images/i_money.png') }}" alt="Economy" class="img-responsive">
                            <ul class="list_tuvankt">
                                @foreach($caseAdviceEconomies as $caseAdviceEconomy)
                                    <li><a href="javascript:void(0)">✓ {{ \App\Libraries\Helpers\Utility::getValueByLocale($caseAdviceEconomy, 'name') }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 boxleft_vechungtoi boxmH display_table">
                    <div class="table_content">
                        <div class="row">
                            <div class="col-lg-8">
                                <img src="{{ asset('themes/images/i_balance.png') }}" alt="Law" class="img-responsive">
                                <ul class="list_tuvanpl">
                                    @foreach($caseAdviceLaws as $caseAdviceLaw)
                                        <li><a href="javascript:void(0)">✓ {{ \App\Libraries\Helpers\Utility::getValueByLocale($caseAdviceLaw, 'name') }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-lg-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="tintucnoibat">
            <div class="container">
                <h2 class="title_line">TIN TỨC NỔI BẬT TRONG NGÀY</h2>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="owl_tintucnoibat owl-carousel owl-theme">
                            <div class="item">
                                <a href="{{ action('Frontend\ArticleController@tintuc') }}">
                        <span class="table_content">
                          <img src="{{ asset('themes/images/i_tc.png') }}" alt="" class="img-responsive">
                        </span>
                                </a>
                                <p>TÀI CHÍNH NGÂN HÀNG</p>
                            </div>
                            <div class="item">
                                <a href="{{ action('Frontend\ArticleController@tintuc') }}">
                        <span class="table_content">
                          <img src="{{ asset('themes/images/i_pl.png') }}" alt="" class="img-responsive">
                        </span>
                                </a>
                                <p>PHÁP LUẬT</p>
                            </div>
                            <div class="item">
                                <a href="{{ action('Frontend\ArticleController@tintuc') }}">
                        <span class="table_content">
                          <img src="{{ asset('themes/images/i_bds.png') }}" alt="" class="img-responsive">
                        </span>
                                </a>
                                <p>BẤT ĐỘNG SẢN</p>
                            </div>
                            <div class="item">
                                <a href="{{ action('Frontend\ArticleController@tintuc') }}">
                        <span class="table_content">
                          <img src="{{ asset('themes/images/i_ck.png') }}" alt="" class="img-responsive">
                        </span>
                                </a>
                                <p>ĐẦU TƯ – CHỨNG KHOÁN</p>
                            </div>
                            <div class="item">
                                <a href="{{ action('Frontend\ArticleController@tintuc') }}">
                        <span class="table_content">
                          <img src="{{ asset('themes/images/i_kns.png') }}" alt="" class="img-responsive">
                        </span>
                                </a>
                                <p>KỸ NĂNG SỐNG</p>
                            </div>
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
                                <li><a href="javascript:void(0)">✓ {{ \App\Libraries\Helpers\Utility::getValueByLocale($certificate, 'name') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop