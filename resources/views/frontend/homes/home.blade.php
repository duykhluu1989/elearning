@extends('frontend.layouts.main')

@section('page_heading', 'Home')

@section('section')

    @include('frontend.layouts.partials.header')

    <section class="hotdeal">
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
                                        <a href="javascript:void(0)"><img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'title') }}" class="img-responsive"></a>
                                        <div class="ticker">
                                            <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->view_count) }}</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->bought_count) }}</span></p>
                                        </div>
                                        <div class="box_item_content">
                                            <p>Duis eget vulputate eros. Donec vehicula egetctus</p>
                                            <p>(Aenean hendrerit ipsum)</p>
                                            <p class="gia">{{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . ' VND' }}</p>
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
                                        <a href="javascript:void(0)"><img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'title') }}" class="img-responsive"></a>
                                        <div class="ticker">
                                            <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->view_count) }}</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->bought_count) }}</span></p>
                                        </div>
                                        <div class="box_item_content">
                                            <p>Duis eget vulputate eros. Donec vehicula egetctus</p>
                                            <p>(Aenean hendrerit ipsum)</p>
                                            <p class="gia">{{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . ' VND' }}</p>
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
                                        <a href="javascript:void(0)"><img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'title') }}" class="img-responsive"></a>
                                        <div class="ticker">
                                            <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->view_count) }}</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->bought_count) }}</span></p>
                                        </div>
                                        <div class="box_item_content">
                                            <p>Duis eget vulputate eros. Donec vehicula egetctus</p>
                                            <p>(Aenean hendrerit ipsum)</p>
                                            <p class="gia">{{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . ' VND' }}</p>
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
                    <a href="#"><img src="{{ asset('themes/images/qc.jpg') }}" alt="" class="img-responsive"></a>
                    <a href="#" class="btnClose"></a>
                </div>
                <div class="qc_right hidden-xs hidden-sm">
                    <a href="#"><img src="{{ asset('themes/images/qc.jpg') }}" alt="" class="img-responsive"></a>
                    <a href="#" class="btnClose"></a>
                </div>
                <div class="row">
                    <h3 class="title_line">Góc nhìn Giảng viên</h3>
                    <div class="col-lg-12">
                        <div class="owl_chuyengia owl-carousel owl-theme">
                            <div class="item">
                                <p class="name">Chuyên gia Phạm Chi Lan</p>
                                <a href="#"><img src="{{ asset('themes/images/cg01.jpg') }}" alt="" class="img-responsive"></a>
                                <p>Chuyên gia Phạm Chi Lan: Mở rộng hạn điền sẽ là chìa khóa...</p>
                            </div>
                            <div class="item">
                                <p class="name">TS Cấn Văn Lực</p>
                                <a href="#"><img src="{{ asset('themes/images/cg02.jpg') }}" alt="" class="img-responsive"></a>
                                <p>Thiếu vốn trung dài hạn khiến áp lực tăng lãi suất rất lớn</p>
                            </div>
                            <div class="item">
                                <p class="name">TS. Nguyễn Trí Hiếu</p>
                                <a href="#"><img src="{{ asset('themes/images/cg03.jpg') }}" alt="" class="img-responsive"></a>
                                <p>Chặn đà rơi tỷ giá</p>
                            </div>
                            <div class="item">
                                <p class="name">TS.LS. Bùi Quang Tín</p>
                                <a href="#"><img src="{{ asset('themes/images/cg04.jpg') }}" alt="" class="img-responsive"></a>
                                <p>Ồ ạt phát hành chứng chỉ tiền gửi “siêu lãi suất“</p>
                            </div>
                            <div class="item">
                                <p class="name">GS.TS Trần Ngọc Thơ</p>
                                <a href="#"><img src="{{ asset('themes/images/cg05.jpg') }}" alt="" class="img-responsive"></a>
                                <p>Dầu giảm 1 USD, tạo ra bao nhiêu việc làm?</p>
                            </div>
                            <div class="item">
                                <p class="name">Chuyên gia Phạm Chi Lan</p>
                                <a href="#"><img src="{{ asset('themes/images/cg01.jpg') }}" alt="" class="img-responsive"></a>
                                <p>Chuyên gia Phạm Chi Lan: Mở rộng hạn điền sẽ là chìa khóa...</p>
                            </div>
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
                                            <a href="javascript:void(0)"><img src="{{ $course->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'title') }}" class="img-responsive"></a>
                                            <div class="ticker">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->view_count) }}</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::formatNumber($course->bought_count) }}</span></p>
                                            </div>
                                            <div class="box_item_content">
                                                <p>Duis eget vulputate eros. Donec vehicula egetctus</p>
                                                <p>(Aenean hendrerit ipsum)</p>
                                                <p class="gia">{{ \App\Libraries\Helpers\Utility::formatNumber($course->price) . ' VND' }}</p>
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
                            <h2>HỌC VIÊN NÓI GÌ VỀ CHÚNG TÔI</h2>
                            <div class="owl_vechungtoi owl-carousel owl-theme">
                                <div class="item">
                                    <div class="row">
                                        <div class="col-lg-4 text-center">
                                            <img src="{{ asset('themes/images/hv01.jpg') }}" alt="" class="img-responsive">
                                        </div>
                                        <div class="col-lg-8">
                                            <h4>Aenean hendrerit ipsum ac risus finibus</h4>
                                            <p>Donec nec odio sed nisi gravida malesuada. Praesent vestibulum, ligula quis consequat malesuada</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p>Pellentesque ut rhoncus massa. Fusce rhoncus eu nunc vitae vehicula. Praesent eleifend rutrum condimentum. Phasellus vehicula dignissim mi, ut ultricies mauris vulputate posuere. Donec et tempor tortor. Sed volutpat dolor eget leo tincidunt, nec iaculis risus ullamcorper. Aliquam non felis sed dui eleifend molestie. Suspendisse quis diam sit amet ligula ultricies mattis. Morbi pharetra dictum ligula, ut imperdiet lacus posuere sed. Sed fermentum ante non sem gravida convallis.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <img src="{{ asset('themes/images/hv01.jpg') }}" alt="" class="img-responsive">
                                        </div>
                                        <div class="col-lg-8">
                                            <h4>Aenean hendrerit ipsum ac risus finibus</h4>
                                            <p>Donec nec odio sed nisi gravida malesuada. Praesent vestibulum, ligula quis consequat malesuada</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p>Pellentesque ut rhoncus massa. Fusce rhoncus eu nunc vitae vehicula. Praesent eleifend rutrum condimentum. Phasellus vehicula dignissim mi, ut ultricies mauris vulputate posuere. Donec et tempor tortor. Sed volutpat dolor eget leo tincidunt, nec iaculis risus ullamcorper. Aliquam non felis sed dui eleifend molestie. Suspendisse quis diam sit amet ligula ultricies mattis. Morbi pharetra dictum ligula, ut imperdiet lacus posuere sed. Sed fermentum ante non sem gravida convallis.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <img src="{{ asset('themes/images/hv01.jpg') }}" alt="" class="img-responsive">
                                        </div>
                                        <div class="col-lg-8">
                                            <h4>Aenean hendrerit ipsum ac risus finibus</h4>
                                            <p>Donec nec odio sed nisi gravida malesuada. Praesent vestibulum, ligula quis consequat malesuada</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p>Pellentesque ut rhoncus massa. Fusce rhoncus eu nunc vitae vehicula. Praesent eleifend rutrum condimentum. Phasellus vehicula dignissim mi, ut ultricies mauris vulputate posuere. Donec et tempor tortor. Sed volutpat dolor eget leo tincidunt, nec iaculis risus ullamcorper. Aliquam non felis sed dui eleifend molestie. Suspendisse quis diam sit amet ligula ultricies mattis. Morbi pharetra dictum ligula, ut imperdiet lacus posuere sed. Sed fermentum ante non sem gravida convallis.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <img src="{{ asset('themes/images/hv01.jpg') }}" alt="" class="img-responsive">
                                        </div>
                                        <div class="col-lg-8">
                                            <h4>Aenean hendrerit ipsum ac risus finibus</h4>
                                            <p>Donec nec odio sed nisi gravida malesuada. Praesent vestibulum, ligula quis consequat malesuada</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p>Pellentesque ut rhoncus massa. Fusce rhoncus eu nunc vitae vehicula. Praesent eleifend rutrum condimentum. Phasellus vehicula dignissim mi, ut ultricies mauris vulputate posuere. Donec et tempor tortor. Sed volutpat dolor eget leo tincidunt, nec iaculis risus ullamcorper. Aliquam non felis sed dui eleifend molestie. Suspendisse quis diam sit amet ligula ultricies mattis. Morbi pharetra dictum ligula, ut imperdiet lacus posuere sed. Sed fermentum ante non sem gravida convallis.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4"></div>
                    </div>

                </div>
            </div>
        </section>

        <section class="giaovien">
            <div class="container">
                <h3 class="title_line">ĐỘI NGŨ GIẢNG VIÊN</h3>
                <p class="text-center mb60"> Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="owl_giaovien owl-carousel owl-theme">
                            <div class="item">
                                <div class="box_item_gv">
                                    <a href="#">
                                        <img src="{{ asset('themes/images/gv01.png') }}" alt="" class="img-responsive">
                                        <p class="box_item_content_gv">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                                    </a>
                                </div>
                            </div>
                            <div class="item">
                                <div class="box_item_gv">
                                    <a href="#">
                                        <img src="{{ asset('themes/images/gv02.png') }}" alt="" class="img-responsive">
                                        <p class="box_item_content_gv">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                                    </a>
                                </div>
                            </div>
                            <div class="item">
                                <div class="box_item_gv">
                                    <a href="#">
                                        <img src="{{ asset('themes/images/gv03.png') }}" alt="" class="img-responsive">
                                        <p class="box_item_content_gv">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                                    </a>
                                </div>
                            </div>
                            <div class="item">
                                <div class="box_item_gv">
                                    <a href="#">
                                        <img src="{{ asset('themes/images/gv04.png') }}" alt="" class="img-responsive">
                                        <p class="box_item_content_gv">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                                    </a>
                                </div>
                            </div>
                            <div class="item">
                                <div class="box_item_gv">
                                    <a href="#">
                                        <img src="{{ asset('themes/images/gv05.png') }}" alt="" class="img-responsive">
                                        <p class="box_item_content_gv">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                                    </a>
                                </div>
                            </div>
                            <div class="item">
                                <div class="box_item_gv">
                                    <a href="#">
                                        <img src="{{ asset('themes/images/gv01.png') }}" alt="" class="img-responsive">
                                        <p class="box_item_content_gv">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="dmkh bg_gray">
            <div class="container">
                <h3 class="title_line">DANH MỤC KHOÁ HỌC</h3>
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <ul class="list_khoahoc">
                            <li><a href="">✓ Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
                            <li><a href="">✓ Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi. Aliquam ornare mauris faucibus, accumsan orci cursus, scelerisque nulla. </a></li>
                            <li><a href="">✓ Donec tincidunt, mi nec porta porttitor, tellus arcu ultricies erat, ac iaculis arcu odio sed turpis. </a></li>
                            <li><a href="">✓ Pellentesque ac ante mollis, consequat neque nec, scelerisque dui. </a></li>
                            <li><a href="">✓ Pellentesque rutrum tellus justo, a lobortis leo fringilla vitae.</a></li>
                            <li><a href="">✓ Vivamus at magna non felis posuere ultrices. Vestibulum at lectus turpis. Donec elementum odio id quam auctor efficitur.</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="tuvanphapluat">
            <div class="container-fluid">
                <h2 class="title_line">TƯ VẤN KINH TẾ VÀ PHÁP LUẬT MIỄN PHÍ</h2>
                <div class="col-lg-6 boxright_vechungtoi boxmH">
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-8">
                            <img src="{{ asset('themes/images/i_money.png') }}" alt="" class="img-responsive">
                            <ul class="list_tuvankt">
                                <li><a href="">✓ Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
                                <li><a href="">✓ Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi. Aliquam ornare mauris faucibus, accumsan orci cursus, scelerisque nulla. </a></li>
                                <li><a href="">✓ Donec tincidunt, mi nec porta porttitor, tellus arcu ultricies erat, ac iaculis arcu odio sed turpis. </a></li>
                                <li><a href="">✓ Pellentesque ac ante mollis, consequat neque nec, scelerisque dui. </a></li>
                                <li><a href="">✓ Pellentesque rutrum tellus justo, a lobortis leo fringilla vitae.</a></li>
                                <li><a href="">✓ Vivamus at magna non felis posuere ultrices. Vestibulum at lectus turpis. Donec elementum odio id quam auctor efficitur.</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 boxleft_vechungtoi boxmH display_table">
                    <div class="table_content">
                        <div class="row">
                            <div class="col-lg-8">
                                <img src="{{ asset('themes/images/i_balance.png') }}" alt="" class="img-responsive">
                                <ul class="list_tuvanpl">
                                    <li><a href="">✓ Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
                                    <li><a href="">✓ Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi. Aliquam ornare mauris faucibus, accumsan orci cursus, scelerisque nulla. </a></li>
                                    <li><a href="">✓ Donec tincidunt, mi nec porta porttitor, tellus arcu ultricies erat, ac iaculis arcu odio sed turpis. </a></li>
                                    <li><a href="">✓ Pellentesque ac ante mollis, consequat neque nec, scelerisque dui. </a></li>
                                    <li><a href="">✓ Pellentesque rutrum tellus justo, a lobortis leo fringilla vitae.</a></li>
                                    <li><a href="">✓ Vivamus at magna non felis posuere ultrices. Vestibulum at lectus turpis. Donec elementum odio id quam auctor efficitur.</a></li>
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
                                <a href="#">
                        <span class="table_content">
                          <img src="{{ asset('themes/images/i_tc.png') }}" alt="" class="img-responsive">
                        </span>
                                </a>
                                <p>TÀI CHÍNH NGÂN HÀNG</p>
                            </div>
                            <div class="item">
                                <a href="#">
                        <span class="table_content">
                          <img src="{{ asset('themes/images/i_pl.png') }}" alt="" class="img-responsive">
                        </span>
                                </a>
                                <p>PHÁP LUẬT</p>
                            </div>
                            <div class="item">
                                <a href="#">
                        <span class="table_content">
                          <img src="{{ asset('themes/images/i_bds.png') }}" alt="" class="img-responsive">
                        </span>
                                </a>
                                <p>BẤT ĐỘNG SẢN</p>
                            </div>
                            <div class="item">
                                <a href="#">
                        <span class="table_content">
                          <img src="{{ asset('themes/images/i_ck.png') }}" alt="" class="img-responsive">
                        </span>
                                </a>
                                <p>ĐẦU TƯ – CHỨNG KHOÁN</p>
                            </div>
                            <div class="item">
                                <a href="#">
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
                <h3 class="title_line">CẤP CHỨNG CHỈ KHOÁ HỌC</h3>
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <ul class="list_khoahoc">
                            <li><a href="">✓ Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
                            <li><a href="">✓ Aenean sapien libero, tincidunt nec lorem sed, pretium consequat nisi. Aliquam ornare mauris faucibus, accumsan orci cursus, scelerisque nulla. </a></li>
                            <li><a href="">✓ Donec tincidunt, mi nec porta porttitor, tellus arcu ultricies erat, ac iaculis arcu odio sed turpis. </a></li>
                            <li><a href="">✓ Pellentesque ac ante mollis, consequat neque nec, scelerisque dui. </a></li>
                            <li><a href="">✓ Pellentesque rutrum tellus justo, a lobortis leo fringilla vitae.</a></li>
                            <li><a href="">✓ Vivamus at magna non felis posuere ultrices. Vestibulum at lectus turpis. Donec elementum odio id quam auctor efficitur.</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>


    </main>

@stop