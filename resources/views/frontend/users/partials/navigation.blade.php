<ul class="list_navLeft">
    <li class="{{ (request()->is('account') ? 'active' : '') }}"><a href="{{ (request()->is('account') ? 'javascript:void(0)' : action('Frontend\UserController@editAccount')) }}">@lang('theme.account_general')</a></li>
    <li class="{{ (request()->is('account/order*') ? 'active' : '') }}"><a href="{{ (request()->is('account/order*') ? 'javascript:void(0)' : action('Frontend\UserController@adminOrder')) }}">@lang('theme.user_order')</a></li>
    <li class="{{ (request()->is('account/course*') ? 'active' : '') }}"><a href="{{ (request()->is('account/course*') ? 'javascript:void(0)' : action('Frontend\UserController@adminCourse')) }}">@lang('theme.user_course')</a></li>
</ul>