<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview{{ (request()->is('admin/user*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-user"></i><span>Thành Viên</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/user') ? 'active' : '') }}">
                        <a href="{{ action('Backend\UserController@adminUser') }}">{{ \App\Models\User::ADMIN_TRUE_LABEL }}</a>
                    </li>
                    <li class="{{ (request()->is('admin/userStudent') ? 'active' : '') }}">
                        <a href="{{ action('Backend\UserController@adminUserStudent') }}">{{ \App\Models\User::ADMIN_FALSE_LABEL }}</a>
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
                </ul>
            </li>
        </ul>
    </section>
</aside>