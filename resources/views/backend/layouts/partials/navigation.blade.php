<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview{{ (request()->is('admin/order*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-inbox"></i><span>Đơn Hàng</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/order') ? 'active' : '') }}">
                        <a href="{{ action('Backend\OrderController@adminOrder') }}">Đơn Hàng</a>
                    </li>
                </ul>
            </li>
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
                    <li class="{{ (request()->is('admin/courseReview') ? 'active' : '') }}">
                        <a href="{{ action('Backend\CourseController@adminCourseReview') }}">Nhận Xét Khóa Học</a>
                    </li>
                </ul>
            </li>
            <li class="treeview{{ (request()->is('admin/news*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-rss"></i><span>Tin Tức</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/newsCategory*') ? 'active' : '') }}">
                        <a href="{{ action('Backend\NewsController@adminCategory') }}">Chuyên Mục</a>
                    </li>
                    <li class="{{ (request()->is('admin/newsArticle*') ? 'active' : '') }}">
                        <a href="{{ action('Backend\NewsController@adminArticle') }}">Tin Tức</a>
                    </li>
                </ul>
            </li>
            <li class="{{ (request()->is('admin/discount*') ? 'active' : '') }}">
                <a href="{{ action('Backend\DiscountController@adminDiscount') }}"><i class="fa fa-tags"></i><span>Mã Giảm Giá</span></a>
            </li>
            <li class="{{ (request()->is('admin/widget*') ? 'active' : '') }}">
                <a href="{{ action('Backend\WidgetController@adminWidget') }}"><i class="fa fa-th"></i><span>Tiện Ích</span></a>
            </li>
            <li class="treeview{{ (request()->is('admin/article*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-file-text"></i><span>Bài Viết</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/article') ? 'active' : '') }}">
                        <a href="{{ action('Backend\ArticleController@adminArticle') }}">Bài Viết Chuyên Gia</a>
                    </li>
                    <li class="{{ (request()->is('admin/articleStatic') ? 'active' : '') }}">
                        <a href="{{ action('Backend\ArticleController@adminArticleStatic') }}">Trang Tĩnh</a>
                    </li>
                </ul>
            </li>
            <li class="{{ (request()->is('admin/advice*') ? 'active' : '') }}">
                <a href="{{ action('Backend\CaseAdviceController@adminCaseAdvice') }}"><i class="fa fa-university"></i><span>Tư Vấn</span></a>
            </li>
            <li class="treeview{{ (request()->is('admin/certificate*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-certificate"></i><span>Chứng Chỉ</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/certificate') ? 'active' : '') }}">
                        <a href="{{ action('Backend\CertificateController@adminCertificate') }}">Chứng Chỉ</a>
                    </li>
                    <li class="{{ (request()->is('admin/certificateApply') ? 'active' : '') }}">
                        <a href="{{ action('Backend\CertificateController@adminCertificateApply') }}">Đăng Kí Cấp Chứng Chỉ</a>
                    </li>
                </ul>
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
                    <li class="{{ (request()->is('admin/userCollaborator') ? 'active' : '') }}">
                        <a href="{{ action('Backend\UserController@adminUserCollaborator') }}">Cộng Tác Viên</a>
                    </li>
                    <li class="{{ (request()->is('admin/userTeacher') ? 'active' : '') }}">
                        <a href="{{ action('Backend\UserController@adminUserTeacher') }}">Giảng Viên</a>
                    </li>
                    <li class="{{ (request()->is('admin/userExpert') ? 'active' : '') }}">
                        <a href="{{ action('Backend\UserController@adminUserExpert') }}">Chuyên Gia</a>
                    </li>
                </ul>
            </li>
            <li class="{{ (request()->is('admin/role*') ? 'active' : '') }}">
                <a href="{{ action('Backend\RoleController@adminRole') }}"><i class="fa fa-shield"></i><span>Phân Quyền</span></a>
            </li>
            <li class="treeview{{ (request()->is('admin/theme*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-paint-brush"></i><span>Giao Diện</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/theme/menu') ? 'active' : '') }}">
                        <a href="{{ action('Backend\ThemeController@adminMenu') }}">Menu</a>
                    </li>
                    <li class="{{ (request()->is('admin/theme/footer') ? 'active' : '') }}">
                        <a href="{{ action('Backend\ThemeController@adminFooter') }}">Footer</a>
                    </li>
                </ul>
            </li>
            <li class="treeview{{ (request()->is('admin/setting*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-cog"></i><span>Cài Đặt</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/setting') ? 'active' : '') }}">
                        <a href="{{ action('Backend\SettingController@adminSetting') }}">Tổng quan</a>
                    </li>
                    <li class="{{ (request()->is('admin/setting/collaborator') ? 'active' : '') }}">
                        <a href="{{ action('Backend\SettingController@adminSettingCollaborator') }}">Chính Sách Cộng Tác Viên</a>
                    </li>
                    <li class="{{ (request()->is('admin/setting/social') ? 'active' : '') }}">
                        <a href="{{ action('Backend\SettingController@adminSettingSocial') }}">Mạng Xã Hội</a>
                    </li>
                    <li class="{{ (request()->is('admin/setting/paymentMethod') ? 'active' : '') }}">
                        <a href="{{ action('Backend\PaymentMethodController@adminPaymentMethod') }}">Phương Thức Thanh Toán</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
</aside>