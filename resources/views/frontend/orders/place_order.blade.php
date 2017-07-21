@extends('frontend.layouts.main')

@section('page_heading', trans('theme.checkout'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.checkout')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="khoahoc_dachon">
                            <p>2 khóa học đã chọn</p>
                            <div class="row item_khoahoc_dachon">
                                <div class="col-xs-4">
                                    <a href="#"><img src="images/img_giohang.png" alt="" class="img-responsive"></a>
                                </div>
                                <div class="col-xs-8 pl0 pr0">
                                    <p><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></p>
                                    <p class="price">500.000 ₫</p>
                                </div>
                            </div>
                            <div class="row item_khoahoc_dachon">
                                <div class="col-xs-4">
                                    <a href="#"><img src="images/img_giohang.png" alt="" class="img-responsive"></a>
                                </div>
                                <div class="col-xs-8 pl0 pr0">
                                    <p><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></p>
                                    <p class="price">500.000 ₫</p>
                                </div>
                            </div>
                            <div class="row item_khoahoc_dachon">
                                <div class="col-xs-4">
                                    <a href="#"><img src="images/img_giohang.png" alt="" class="img-responsive"></a>
                                </div>
                                <div class="col-xs-8 pl0 pr0">
                                    <p><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></p>
                                    <p class="price">500.000 ₫</p>
                                </div>
                            </div>
                            <div class="row item_khoahoc_dachon">
                                <div class="col-xs-4">
                                    <a href="#"><img src="images/img_giohang.png" alt="" class="img-responsive"></a>
                                </div>
                                <div class="col-xs-8 pl0 pr0">
                                    <p><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></p>
                                    <p class="price">500.000 ₫</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="" method="POST" role="form" class="frm_maKM">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" placeholder="Nhập mã khuyến mãi">
                                        </div>
                                        <button type="submit" class="btn btn-lg btn-block btnRed">ÁP DỤNG</button>
                                    </form>
                                    <div class="table-responsive table_hocphi">
                                        <table class="table table-hover">
                                            <tbody>
                                            <tr>
                                                <th>Học phí gốc </th>
                                                <td>2.000.000₫</td>
                                            </tr>
                                            <tr>
                                                <th>Giảm giá</th>
                                                <td>0₫</td>
                                            </tr>
                                            <tr>
                                                <th>Học phí gốc</th>
                                                <td>2.000.000₫</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <p>Chọn phương thức thanh toán</p>
                            <p>Số tiền cần thanh toán: <b><span>2.000.000 ₫</span></b></p>
                            <form action="" method="POST" role="form">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Giao mã kích hoạt và thu tiền tận nơi (COD)</a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse in">
                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" id="" placeholder="nicker">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" id="" placeholder="nickey3000@gmail.com">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" id="" placeholder="0908911493">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" id="" placeholder="Địa chỉ (*)">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <select name="" id="" class="form-control" required="required">
                                                                <option value="">Tỉnh /TP </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <select name="" id="" class="form-control" required="required">
                                                                <option value="">Quận/ Huyện </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <textarea name="" id="" class="form-control" rows="8" required="required" placeholder="Ghi chú"></textarea>
                                                </div>
                                                <p>Bạn cần điền đầy đủ các thông tin trên</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Thanh toán bằng mã thẻ cào điện thoại</a>
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p class="alert alert-success" role="alert">caydenthan.vn sẽ hoàn lại số tiền dư sau khi bạn thanh toán thành công vào số điện thoại mà bạn cung cấp.</p>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label for="">Service Cod</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" name="brand" id="" value="" checked="checked">
                                                                    Viettel
                                                                </label>
                                                                <label>
                                                                    <input type="radio" name="brand" id="" value="" >
                                                                    Mobilephone
                                                                </label>
                                                                <label>
                                                                    <input type="radio" name="brand" id="" value="">
                                                                    Vinaphone
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label for="">Mã thẻ cào (*)</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form-control" id="" placeholder="Nhập chữ số dưới lớp ">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label for="">Sỗ seri thẻ (*)</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form-control" id="" placeholder="Nhập số seri in trên thẻ cào">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Thanh toán bằng thẻ ATM có đăng ký Internet Banking (qua cổng Onepay)</a>
                                            </h4>
                                        </div>
                                        <div id="collapseThree" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>* Khóa học sẽ được kích hoạt ngay sau khi bạn thanh toán thành công.</p>
                                                <img src="images/bank.png" alt="" class="img-responsive">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Thanh toán bằng thẻ quốc tế Visa/Mastercard (qua cổng Onepay)</a>
                                            </h4>
                                        </div>
                                        <div id="collapse4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <img src="images/logo-visa-master.png" alt="" class="img-responsive">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">Chuyển khoản qua Ngân hàng hoặc nộp tại cây ATM gần nhất</a>
                                            </h4>
                                        </div>
                                        <div id="collapse5" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>* Khóa học sẽ được kích hoạt sau khi caydenthan.vn kiểm tra tài khoản và xác nhận việc thanh toán của bạn thành công. (Thời gian kiểm tra và xác nhận tài khoản ít nhất là 12 giờ)</p>
                                                <h4>Chuyển khoản ngân hàng</h4>
                                                <p>Bạn có thể đến bất kỳ ngân hàng nào ở Việt Nam (hoặc sử dụng Internet Banking) để chuyển tiền theo thông tin bên dưới:</p>
                                                <p>• Số tài khoản: 0531 0025 11245. <br>
                                                    • Chủ tài khoản: Công ty cổ phần DREAM VIET EDUCATION. <br>
                                                    • Ngân hàng: Ngân hàng Vietcombank, Chi nhánh Đông Sài Gòn, TP.HCM.<br>
                                                    Ghi chú khi chuyển khoản:<br><br>
                                                    • Tại mục "Ghi chú" khi chuyển khoản, bạn ghi rõ: Số điện thoại - Họ và tên - Email đăng ký học - Mã đơn hàng. <br>
                                                    • Ví dụ: 0909090909 - Nguyen Thi Huong Lan - caydenthan@gmail.com - 2313123<br>
                                                    Chuyển tiền qua Paypal<br><br>
                                                    • Địa chỉ email nhận tiền: ketoan@kyna.vn <br>
                                                    • Tại mục "Message" khi chuyển tiền, bạn ghi rõ: Số điện thoại - Họ và tên - Email đăng ký học - Khóa học đăng ký. <br>
                                                    • Tỉ giá áp dụng 1 USD = 21.000 VND (tỉ giá trên PayPal).</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">Đóng tiền trực tiếp tại văn phòng của caydenthan.vn</a>
                                            </h4>
                                        </div>
                                        <div id="collapse6" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Địa chỉ văn phòng:<br>
                                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum, facere sapiente, magni perferendis dicta voluptatibus ea natus sit laborum libero eligendi voluptatum aspernatur nulla architecto tenetur, velit provident. Aperiam, similique!</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <a href="{{ action('Frontend\OrderController@editCart') }}" class="btn btn-lg btnYellow">@lang('theme.back_to_cart')</a>
                                    </div>
                                    <div class="col-xs-6">
                                        <button type="submit" class="btn btn-lg btnRed pull-right">@lang('theme.place_order')</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>

@stop
