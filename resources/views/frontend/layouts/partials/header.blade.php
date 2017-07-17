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
                    <a href="{{ action('Frontend\HomeController@home') }}" class="navbar-brand"></a>
                </div>
                <div class="collapse navbar-collapse js-navbar-collapse">
                    <ul class="nav navbar-nav main_menu">

                        @include('frontend.layouts.partials.menu')

                    </ul>
                    <ul class="nav navbar-nav navbar-right lang">
                        <li>
                            <div class="search-button">
                                <a href="#" class="search-toggle" data-selector="#header-1"></a>
                            </div>
                        </li>

                        @if(auth()->guest())

                            <li><a href="#modal_dangky" data-toggle="modal">@lang('theme.sign_up')</a></li>
                            <li><a href="#modal_dangnhap" data-toggle="modal">@lang('theme.sign_in')</a></li>

                        @endif

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

@if(auth()->guest())

    <div id="modal_dangky" class="modal fade modal_general" data-backdrop="static" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">@lang('theme.sign_up') <span class="logo_small"><img src="{{ asset('themes/images/logo_small.png') }}" alt="Logo" class="img-responsive"></span></h4>
                </div>
                <div class="modal-body">
                    <form action="{{ action('Frontend\UserController@register') }}" method="POST" role="form" class="frm_dangky" id="SignUpForm">
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" placeholder="@lang('theme.username')">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="@lang('theme.password')">
                        </div>
                        <div class="form-group">
                            <p class="text-center"><a class="btn-link" href="sample.php">Hướng dẫn đăng ký</a></p>
                        </div>
                        <button type="submit" class="btn btn-block btnDangky">@lang('theme.sign_up')</button>
                        <button type="button" class="btn btn-block btnDangnhap"><i class="fa fa-facebook-square" aria-hidden="true"></i> ĐĂNG NHẬP BẰNG FACEBOOK</button>
                        {{ csrf_field() }}
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

    @push('scripts')
        <script type="text/javascript">
            $('#SignUpForm').submit(function(e) {
                e.preventDefault();

                var formElem = $(this);

                formElem.find('input').each(function() {
                    $(this).parent().removeClass('has-error').find('span[class="help-block"]').first().remove();
                });

                $.ajax({
                    url: '{{ action('Frontend\UserController@register') }}',
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '&' + formElem.serialize(),
                    success: function(result) {
                        if(result)
                        {
                            if(result == 'Success')
                            {
                                $('#modal_dangky').modal('hide');

                                swal({
                                    title: 'Đăng kí thành công',
                                    type: 'success',
                                    confirmButtonClass: 'btn-success',
                                    allowEscapeKey: false,
                                    showConfirmButton: false
                                });

                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
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

@endif