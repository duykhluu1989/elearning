<ul class="list_navLeft">
    <li><a href="{{ action('Frontend\UserController@editAccount') }}">@lang('theme.account')</a></li>
    <li class="{{ (request()->is('collaborator') ? 'active' : '') }}"><a href="{{ (request()->is('collaborator') ? 'javascript:void(0)' : action('Frontend\CollaboratorController@editCollaborator')) }}">@lang('theme.account_general')</a></li>
    <li class="{{ (request()->is('collaborator/course') ? 'active' : '') }}"><a href="{{ (request()->is('collaborator/course') ? 'javascript:void(0)' : action('Frontend\CollaboratorController@adminCourse')) }}">@lang('theme.all_course')</a></li>
</ul>