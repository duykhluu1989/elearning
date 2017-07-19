@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($category, 'name'))

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($category, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.category_breadcrumb')

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.category_list')</h5>
                            <ul class="list_navLeft">
                                @foreach($listCategories as $listCategory)
                                    <li<?php echo ($listCategory->id == $category->id ? ' class="active"' : ''); ?>><a href="{{ action('Frontend\CourseController@detailCategory', ['id' => $listCategory->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($listCategory, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($listCategory, 'name') }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <ul class="nav nav-tabs tabs_khoahoc">
                                <li><a class="disabled" href="javascript:void(0)"><h3>@lang('theme.all_course')</h3></a></li>
                                <li><a class="disabled" href="javascript:void(0)">@lang('theme.sort_by')</a></li>
                                <li class="active"><a data-toggle="tab" href="#section_moinhat">Mới nhất</a></li>
                                <li><a data-toggle="tab" href="#section_noibat">Nổi bật</a></li>
                                <li><a data-toggle="tab" href="#section_khuyenmai">Khuyến mãi</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="section_moinhat" class="tab-pane fade in active">
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                            <a href="#modal_xemKH" class="btn btnYellow btn-block" data-toggle="modal">Xem nhanh</a>
                                            <a href="chitietkhoahoc.php" class="btn btnRed btn-block">Xem chi tiết</a>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                            <a href="#modal_xemKH" class="btn btnYellow btn-block" data-toggle="modal">Xem nhanh</a>
                                            <a href="chitietkhoahoc.php" class="btn btnRed btn-block">Xem chi tiết</a>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                            <a href="#modal_xemKH" class="btn btnYellow btn-block" data-toggle="modal">Xem nhanh</a>
                                            <a href="chitietkhoahoc.php" class="btn btnRed btn-block">Xem chi tiết</a>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                            <a href="#modal_xemKH" class="btn btnYellow btn-block" data-toggle="modal">Xem nhanh</a>
                                            <a href="chitietkhoahoc.php" class="btn btnRed btn-block">Xem chi tiết</a>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                            <a href="#modal_xemKH" class="btn btnYellow btn-block" data-toggle="modal">Xem nhanh</a>
                                            <a href="chitietkhoahoc.php" class="btn btnRed btn-block">Xem chi tiết</a>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                            <a href="#modal_xemKH" class="btn btnYellow btn-block" data-toggle="modal">Xem nhanh</a>
                                            <a href="chitietkhoahoc.php" class="btn btnRed btn-block">Xem chi tiết</a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <ul class="pagination">
                                                <li><a href="#">&laquo;</a></li>
                                                <li class="active"><a href="#">1</a></li>
                                                <li><a href="#">2</a></li>
                                                <li><a href="#">3</a></li>
                                                <li><a href="#">4</a></li>
                                                <li><a href="#">5</a></li>
                                                <li><a href="#">&raquo;</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div id="section_noibat" class="tab-pane fade">
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <ul class="pagination">
                                                <li><a href="#">&laquo;</a></li>
                                                <li class="active"><a href="#">1</a></li>
                                                <li><a href="#">2</a></li>
                                                <li><a href="#">3</a></li>
                                                <li><a href="#">4</a></li>
                                                <li><a href="#">5</a></li>
                                                <li><a href="#">&raquo;</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div id="section_khuyenmai" class="tab-pane fade">
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row item_khoahoc">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <a href="chitietkhoahoc.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <h4><a href="chitietkhoahoc.php">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</a></h4>
                                            <p class="name_gv">Nguyễn Đức Việt</p>
                                            <p class="price">599,000đ</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="ticker2">
                                                <p><span class="view"><i class="fa fa-eye" aria-hidden="true"></i> 800</span> - <span class="buy"><i class="fa fa-money" aria-hidden="true"></i> 200</span></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <ul class="pagination">
                                                <li><a href="#">&laquo;</a></li>
                                                <li class="active"><a href="#">1</a></li>
                                                <li><a href="#">2</a></li>
                                                <li><a href="#">3</a></li>
                                                <li><a href="#">4</a></li>
                                                <li><a href="#">5</a></li>
                                                <li><a href="#">&raquo;</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>

    <div id="modal_xemKH" class="modal fade bs-example-modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">Thành thạo Bootstrap qua 10 website và kiếm tiền từ công việc Freelancer</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5 display_table boxmH">
                            <div class="table_content">
                                <img src="images/img_giohang.png" alt="" class="img-responsive">
                            </div>
                        </div>
                        <div class="col-lg-7 boxmH">
                            <div class="box_info_khoahoc">
                                <p class="big_price"><i class="fa fa-tags" aria-hidden="true"></i> <span class="new_price">599,000đ</span> - <span class="sale">(600,000đ)</span> <span class="sale_percent">-26%</span></p>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <a href="#" class="btn btn-lg btnMuaKH"><i class="fa fa-cart-plus" aria-hidden="true"></i> MUA KHOÁ HỌC</a>
                                        <a href="#" class="btn btn-lg btnThemGH"><i class="fa fa-plus-square-o" aria-hidden="true"></i></i> THÊM VÀO GIỎ HÀNG</a>
                                        <div class="box_sl_baigiang">
                                            <p>Số lượng bài giảng: <span><b>75</b></span></p>
                                            <p>Thời lượng video: <span><b>12h57p</b></span></p>
                                        </div>
                                        <a class="btn btn_face mb10" href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i> Chia sẽ</a>
                                        <a class="btn btn_face" href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Thích</a>
                                    </div>
                                    <div class="col-lg-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop