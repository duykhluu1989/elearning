<ul class="list_navLeft">
    <li><a href="{{ action('Frontend\UserController@editAccount') }}">@lang('theme.account')</a></li>
    <li class="{{ (request()->is('collaborator') ? 'active' : '') }}"><a href="{{ (request()->is('collaborator') ? 'javascript:void(0)' : action('Frontend\CollaboratorController@editCollaborator')) }}">@lang('theme.account_general')</a></li>
    <li class="{{ (request()->is('collaborator/course') ? 'active' : '') }}"><a href="{{ (request()->is('collaborator/course') ? 'javascript:void(0)' : action('Frontend\CollaboratorController@adminCourse')) }}">@lang('theme.get_course_link')</a></li>
    <li class="{{ (request()->is('collaborator/transaction') ? 'active' : '') }}"><a href="{{ (request()->is('collaborator/transaction') ? 'javascript:void(0)' : action('Frontend\CollaboratorController@adminCollaboratorTransaction')) }}">@lang('theme.commission_history')</a></li>
    <li class="{{ (request()->is('collaborator/downLine') ? 'active' : '') }}"><a href="{{ (request()->is('collaborator/downLine') ? 'javascript:void(0)' : action('Frontend\CollaboratorController@adminCollaboratorDownLine')) }}">@lang('theme.collaborator_down_line')</a></li>
</ul>