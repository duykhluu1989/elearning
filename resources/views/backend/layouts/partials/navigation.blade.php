<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview{{ (request()->is('admin/course*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-book"></i><span>Khóa Học</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/courseCategory') ? 'active' : '') }}">
                        <a href="{{ action('Backend\CourseController@adminCategory') }}">Chủ Đề</a>
                    </li>
                    <li class="{{ (request()->is('admin/courseLevel') ? 'active' : '') }}">
                        <a href="{{ action('Backend\CourseController@adminLevel') }}">Cấp Độ</a>
                    </li>
                    <li class="{{ (request()->is('admin/course') ? 'active' : '') }}">
                        <a href="{{ action('Backend\CourseController@adminCourse') }}">Khóa Học</a>
                    </li>
                    <li class="{{ (request()->is('admin/courseTag') ? 'active' : '') }}">
                        <a href="{{ action('Backend\CourseController@adminTag') }}">Nhãn</a>
                    </li>
                </ul>
            </li>
            <li class="{{ (request()->is('admin/discount*') ? 'active' : '') }}">
                <a href="{{ action('Backend\DiscountController@adminDiscount') }}"><i class="fa fa-tags"></i><span>Mã Giảm Giá</span></a>
            </li>
            <li class="{{ (request()->is('admin/widget*') ? 'active' : '') }}">
                <a href="{{ action('Backend\WidgetController@adminWidget') }}"><i class="fa fa-th"></i><span>Tiện Ích</span></a>
            </li>
            <li class="treeview{{ (request()->is('admin/user*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-user"></i><span>Thành Viên</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/user') ? 'active' : '') }}">
                        <a href="{{ action('Backend\UserController@adminUser') }}">Quản Trị Viên</a>
                    </li>
                    <li class="{{ (request()->is('admin/userStudent') ? 'active' : '') }}">
                        <a href="{{ action('Backend\UserController@adminUserStudent') }}">Học Viên</a>
                    </li>
                </ul>
            </li>
            <li class="{{ (request()->is('admin/role*') ? 'active' : '') }}">
                <a href="{{ action('Backend\RoleController@adminRole') }}"><i class="fa fa-shield"></i><span>Phân Quyền</span></a>
            </li>
            <li class="treeview{{ (request()->is('admin/setting*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-cog"></i><span>Cài Đặt</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/setting') ? 'active' : '') }}">
                        <a href="{{ action('Backend\SettingController@adminSetting') }}">Tổng quan</a>
                    </li>
                    <li class="{{ (request()->is('admin/setting/paymentMethod') ? 'active' : '') }}">
                        <a href="{{ action('Backend\PaymentMethodController@adminPaymentMethod') }}">Phương Thức Thanh Toán</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
</aside>