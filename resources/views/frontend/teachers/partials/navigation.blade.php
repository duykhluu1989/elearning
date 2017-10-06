<ul class="list_navLeft">
    <li><a href="{{ action('Frontend\UserController@editAccount') }}">@lang('theme.account')</a></li>
    <li class="{{ (request()->is('teacher') ? 'active' : '') }}"><a href="{{ (request()->is('teacher') ? 'javascript:void(0)' : action('Frontend\TeacherController@editTeacher')) }}">@lang('theme.account_general')</a></li>
    <li class="{{ (request()->is('teacher/question') ? 'active' : '') }}"><a href="{{ (request()->is('teacher/question') ? 'javascript:void(0)' : action('Frontend\TeacherController@adminCourseQuestion')) }}">@lang('theme.question')</a></li>
</ul>