<ul class="list_navLeft">
    <li class="{{ (request()->is('account') ? 'active' : '') }}"><a href="{{ (request()->is('account') ? 'javascript:void(0)' : action('Frontend\UserController@editAccount')) }}">@lang('theme.account_general')</a></li>
    <li class="{{ (request()->is('account/order*') ? 'active' : '') }}"><a href="{{ (request()->is('account/order*') ? 'javascript:void(0)' : action('Frontend\UserController@adminOrder')) }}">@lang('theme.user_order')</a></li>
    <li class="{{ (request()->is('account/course*') ? 'active' : '') }}"><a href="{{ (request()->is('account/course*') ? 'javascript:void(0)' : action('Frontend\UserController@adminCourse')) }}">@lang('theme.user_course')</a></li>

    @if(!empty(auth()->user()->collaboratorInformation) && auth()->user()->collaboratorInformation->status != \App\Models\Collaborator::STATUS_PENDING_DB)
        <li><a href="{{ action('Frontend\CollaboratorController@editCollaborator') }}">@lang('theme.collaborator')</a></li>
    @endif

    @if(!empty(auth()->user()->teacherInformation) && auth()->user()->teacherInformation->status != \App\Models\Collaborator::STATUS_PENDING_DB)
        <li><a href="{{ action('Frontend\TeacherController@editTeacher') }}">@lang('theme.teacher_label')</a></li>
    @endif
</ul>