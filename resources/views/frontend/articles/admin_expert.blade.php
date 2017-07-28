@extends('frontend.layouts.main')

@section('page_heading', trans('theme.expert'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.expert')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>Các tác giả</h5>
                            <hr>
                            <ul class="list_navLeft">
                                <li class="active">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <a href="#"><img src="images/cg01.jpg" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-xs-8">
                                            <a href="#">TS. Phan Minh Ngọc</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <a href="#"><img src="images/cg02.jpg" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-xs-8">
                                            <a href="#">Thống đốc Nguyễn Văn Bình</a>
                                            <p><small>Thống đốc NHNN Việt Nam</small></p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <a href="#"><img src="images/cg03.jpg" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-xs-8">
                                            <a href="#">GS.TS Trần Thọ Đạt</a>
                                            <p><small>Hiệu trưởng trường ĐH Kinh tế Quốc Dân</small></p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <a href="#"><img src="images/cg04.jpg" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-xs-8">
                                            <a href="#">NCS. Châu Đình Linh</a>
                                            <p><small>Giảng viên trường Đại học Ngân hàng TPHCM</small></p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <a href="#"><img src="images/cg05.jpg" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-xs-8">
                                            <a href="#">GS.TS Trần Ngọc Thơ</a>
                                            <p><small>Trưởng khoa tài chính Đại học Kinh tế TP.HCM</small></p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <div class="row item_news">
                                <div class="col-lg-3">
                                    <a href="chitiet_gocnhinCH.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                </div>
                                <div class="col-lg-9">
                                    <h4><a href="chitiet_gocnhinCH.php">Phasellus cursus ultricies lacus. Ut a malesuada leo.</a></h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum risus enim, porta nec urna eu, iaculis dapibus lacus. Pellentesque finibus tellus ut mollis pharetra. Praesent vulputate magna eu magna malesuada congue. </p>
                                </div>
                            </div>
                            <div class="row item_news">
                                <div class="col-lg-3">
                                    <a href="chitiet_gocnhinCH.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                </div>
                                <div class="col-lg-9">
                                    <h4><a href="chitiet_gocnhinCH.php">Phasellus cursus ultricies lacus. Ut a malesuada leo.</a></h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum risus enim, porta nec urna eu, iaculis dapibus lacus. Pellentesque finibus tellus ut mollis pharetra. Praesent vulputate magna eu magna malesuada congue. </p>
                                </div>
                            </div>
                            <div class="row item_news">
                                <div class="col-lg-3">
                                    <a href="chitiet_gocnhinCH.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                </div>
                                <div class="col-lg-9">
                                    <h4><a href="chitiet_gocnhinCH.php">Phasellus cursus ultricies lacus. Ut a malesuada leo.</a></h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum risus enim, porta nec urna eu, iaculis dapibus lacus. Pellentesque finibus tellus ut mollis pharetra. Praesent vulputate magna eu magna malesuada congue. </p>
                                </div>
                            </div>
                            <div class="row item_news">
                                <div class="col-lg-3">
                                    <a href="chitiet_gocnhinCH.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                </div>
                                <div class="col-lg-9">
                                    <h4><a href="chitiet_gocnhinCH.php">Phasellus cursus ultricies lacus. Ut a malesuada leo.</a></h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum risus enim, porta nec urna eu, iaculis dapibus lacus. Pellentesque finibus tellus ut mollis pharetra. Praesent vulputate magna eu magna malesuada congue. </p>
                                </div>
                            </div>
                            <div class="row item_news">
                                <div class="col-lg-3">
                                    <a href="chitiet_gocnhinCH.php"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                </div>
                                <div class="col-lg-9">
                                    <h4><a href="chitiet_gocnhinCH.php">Phasellus cursus ultricies lacus. Ut a malesuada leo.</a></h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum risus enim, porta nec urna eu, iaculis dapibus lacus. Pellentesque finibus tellus ut mollis pharetra. Praesent vulputate magna eu magna malesuada congue. </p>
                                </div>
                            </div>
                            <div class="row item_news">
                                <div class="col-lg-3">
                                    <a href="#"><img src="images/img_giohang.png" alt="" class="img-responsive w100p"></a>
                                </div>
                                <div class="col-lg-9">
                                    <h4><a href="chitiet_gocnhinCH.php">Phasellus cursus ultricies lacus. Ut a malesuada leo.</a></h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum risus enim, porta nec urna eu, iaculis dapibus lacus. Pellentesque finibus tellus ut mollis pharetra. Praesent vulputate magna eu magna malesuada congue. </p>
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

        </section>
    </main>

@stop
