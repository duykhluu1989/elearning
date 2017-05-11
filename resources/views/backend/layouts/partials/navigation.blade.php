<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="{{ (request()->is('admin/setting*') ? 'active' : '') }}">
                <a href="{{ action('Backend\SettingController@adminSetting') }}">
                    <i class="fa fa-cog"></i><span>Setting</span>
                </a>
            </li>
        </ul>
    </section>
</aside>