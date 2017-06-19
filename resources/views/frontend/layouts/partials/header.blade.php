<header id="header-1" class="navbar-fixed-top header">
    <div class="menu">
        <nav role="navigation" class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="{{ action('Frontend\HomeController@home') }}" class="navbar-brand">Brand</a>
                </div>
                <div class="collapse navbar-collapse js-navbar-collapse">
                    <ul class="nav navbar-nav main_menu">
                        <li class="dropdown mega-dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="chude.php">CÁC CHỦ ĐỀ <b class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu mega-dropdown-menu">
                                <li class="col-sm-3">
                                    <ul>
                                        <li><a href="#">Men Collection</a></li>
                                        <li><a href="#">View all Collection</a></li>
                                    </ul>
                                </li>
                                <li class="col-sm-3">
                                    <ul>
                                        <li class="dropdown-header">Features</li>
                                        <li><a href="#">Auto Carousel</a></li>
                                        <li><a href="#">Carousel Control</a></li>
                                        <li><a href="#">Left & Right Navigation</a></li>
                                        <li><a href="#">Four Columns Grid</a></li>
                                        <li class="dropdown-header">Fonts</li>
                                        <li><a href="#">Glyphicon</a></li>
                                        <li><a href="#">Google Fonts</a></li>
                                    </ul>
                                </li>
                                <li class="col-sm-3">
                                    <ul>
                                        <li class="dropdown-header">Plus</li>
                                        <li><a href="#">Navbar Inverse</a></li>
                                        <li><a href="#">Pull Right Elements</a></li>
                                        <li><a href="#">Coloured Headers</a></li>
                                        <li><a href="#">Primary Buttons & Default</a></li>
                                    </ul>
                                </li>
                                <li class="col-sm-3">
                                    <ul>
                                        <li class="dropdown-header">Much more</li>
                                        <li><a href="#">Easy to Customize</a></li>
                                        <li><a href="#">Calls to action</a></li>
                                        <li><a href="#">Custom Fonts</a></li>
                                        <li><a href="#">Slide down on Hover</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown mega-dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="ungdung.php">ỨNG DỤNG <b class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu mega-dropdown-menu">
                                <li><a href="#">Lorem ipsum dolor2.</a></li>
                                <li><a href="#">Lorem ipsum dolor sit.</a></li>
                                <li><a href="#">Lorem ipsum dolor sit amet.</a></li>
                            </ul>
                        </li>
                        <li class="dropdown mega-dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="gioithieu.php">GIỚI THIỆU <b class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu mega-dropdown-menu">
                                <li><a href="#">Lorem ipsum dolor.</a></li>
                                <li><a href="#">Lorem ipsum dolor sit.</a></li>
                                <li><a href="#">Lorem ipsum dolor sit amet.</a></li>
                            </ul>
                        </li>
                        <li class="dropdown mega-dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="huongdanthanhtoan.php">HƯỚNG DẪN THANH TOÁN</a></li>
                        <li class="dropdown mega-dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="trogiup.php">TRỢ GIÚP <b class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu mega-dropdown-menu">
                                <li><a href="#">Lorem ipsum dolor.</a></li>
                                <li><a href="#">Lorem ipsum dolor sit.</a></li>
                                <li><a href="#">Lorem ipsum dolor sit amet.</a></li>
                            </ul>
                        </li>
                        <li><a data-toggle="dropdown" class="dropdown-toggle" href="hoptacgiangday.html">HỢP TÁC GIẢNG DẠY</a></li>
                        <li><a data-toggle="dropdown" class="dropdown-toggle" href="quitrinhmuahang.html">QUY TRÌNH MUA HÀNG</a></li>
                        <li><a data-toggle="dropdown" class="dropdown-toggle" href="congtacvien.html">CỘNG TÁC VIÊN</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right lang">
                        <li>
                            <div class="search-button">
                                <a href="#" class="search-toggle" data-selector="#header-1"></a>
                            </div>
                        </li>
                        <li><a href="#modal_dangky" data-toggle="modal">@lang('theme.sign_up')</a></li>
                        <li><a href="#modal_dangnhap" data-toggle="modal">@lang('theme.sign_in')</a></li>
                        <li>
                            <a href="{{ action('Frontend\HomeController@language', ['locale' => 'vi']) }}"><img src="{{ asset('themes/images/vn.jpg') }}" alt="VN" class="img-responsive"></a>
                        </li>
                        <li>
                            <a href="{{ action('Frontend\HomeController@language', ['locale' => 'en']) }}"><img src="{{ asset('themes/images/en.jpg') }}" alt="EN" class="img-responsive"></a>
                        </li>
                        <li>
                            <div class="btnCart">
                                <span class="sum">2</span>
                                <div class="box_cart">
                                    <div class="arrow_top"></div>
                                    <div class="box_cart_content">
                                        <div class="row pro_item">
                                            <div class="col-xs-3">
                                                <a href="#"><img src="{{ asset('themes/images/hv01.jpg') }}" alt="" class="img-responsive"></a>
                                            </div>
                                            <div class="col-xs-9">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec pellentesque ...1</p>
                                                <p class="price">500.000đ</p>
                                            </div>
                                        </div>
                                        <div class="row pro_item">
                                            <div class="col-xs-3">
                                                <a href="#"><img src="{{ asset('themes/images/hv01.jpg') }}" alt="" class="img-responsive"></a>
                                            </div>
                                            <div class="col-xs-9">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec pellentesque ...2</p>
                                                <p class="price">500.000đ</p>
                                            </div>
                                        </div>
                                        <div class="row pro_item">
                                            <div class="col-xs-3">
                                                <a href="#"><img src="{{ asset('themes/images/hv01.jpg') }}" alt="" class="img-responsive"></a>
                                            </div>
                                            <div class="col-xs-9">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec pellentesque ...3</p>
                                                <p class="price">500.000đ</p>
                                            </div>
                                        </div>
                                        <div class="row pro_item">
                                            <div class="col-xs-3">
                                                <a href="#"><img src="{{ asset('themes/images/hv01.jpg') }}" alt="" class="img-responsive"></a>
                                            </div>
                                            <div class="col-xs-9">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec pellentesque ...4</p>
                                                <p class="price">500.000đ</p>
                                            </div>
                                        </div>
                                        <div class="row pro_item">
                                            <div class="col-xs-3">
                                                <a href="#"><img src="{{ asset('themes/images/hv01.jpg') }}" alt="" class="img-responsive"></a>
                                            </div>
                                            <div class="col-xs-9">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec pellentesque ...5</p>
                                                <p class="price">500.000đ</p>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row row_tongtien">
                                        <div class="col-xs-6">
                                            <p>TỔNG TIỀN</p>
                                        </div>
                                        <div class="col-xs-6">
                                            <p class="price pull-right">500.000đ</p>
                                        </div>
                                    </div>
                                    <div class="row row_cart_bottom">
                                        <div class="col-xs-6">
                                            <a href="giohang.php" class="btn btn-block btnGiohang">GIỎ HÀNG</a>
                                        </div>
                                        <div class="col-xs-6">
                                            <a href="thanhtoan.php" class="btn btn-block btnThanhtoan">THANH TOÁN</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <form action="" class="search-box">
                        <input type="text" class="text search-input" placeholder="Type here to search..." />
                    </form>
                </div>
            </div>
        </nav>
    </div>
</header>

<div id="modal_dangky" class="modal fade modal_general" data-backdrop="static" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">Đăng ký <span class="logo_small"><img src="{{ asset('themes/images/logo_small.png') }}" alt="" class="img-responsive"></span></h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" role="form" class="frm_dangky">
                    <div class="form-group">
                        <input type="text" class="form-control" id="" placeholder="Họ và tên">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="" placeholder="Mật khẩu">
                    </div>
                    <div class="form-group">
                        <p class="text-center"><a class="btn-link" href="sample.php">Hướng dẫn đăng ký</a></p>
                    </div>
                    <button type="submit" class="btn btn-block btnDangky">ĐĂNG KÝ</button>
                    <button type="button" class="btn btn-block btnDangnhap"><i class="fa fa-facebook-square" aria-hidden="true"></i> ĐĂNG NHẬP BẰNG FACEBOOK</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal_dangnhap" class="modal fade modal_general" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">Đăng nhập</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" role="form" class="frm_dangky">
                    <div class="form-group">
                        <input type="text" class="form-control" id="" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="" placeholder="Mật khẩu">
                    </div>
                    <button type="submit" class="btn btn-block btnDangky"><i class="fa fa-sign-in" aria-hidden="true"></i> ĐĂNG NHẬP</button>
                    <button type="button" class="btn btn-block btnDangnhap"><i class="fa fa-facebook-square" aria-hidden="true"></i> ĐĂNG NHẬP BẰNG FACEBOOK</button>
                    <div class="form-group">
                        <p class="text-center mt15"><a class="btn-link" href="#modal_quenMK" data-toggle="modal">Quên mật khẩu đăng nhập?</a></p>
                    </div>
                    <div class="modal-footer">
                        <p class="text-center"><a href="#modal_dangky" class="btn-link" data-toggle="modal">Chưa có tài khoản? Đăng ký</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal_quenMK" class="modal fade modal_general" data-backdrop="static" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">Lấy lại mật khẩu</h4>
            </div>
            <div class="modal-body">
                <p>Để lấy lại mật khẩu, bạn nhập email đăng nhập vào ô dưới đây. Sau đó caydenthan.vn sẽ gửi email hướng dẫn bạn khôi phục mật khẩu</p>
                <form action="" method="POST" role="form" class="frm_dangky">
                    <div class="form-group">
                        <input type="text" class="form-control" id="" placeholder="Email của bạn">
                    </div>
                    <button type="submit" class="btn btn-block btnDangky">Tiếp tục →</button>
                </form>
            </div>
        </div>
    </div>
</div>