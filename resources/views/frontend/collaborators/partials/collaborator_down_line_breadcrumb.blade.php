<section class="banner_sub">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ action('Frontend\HomeController@home') }}"><i class="fa fa-home" aria-hidden="true"></i> @lang('theme.home')</a>
                    </li>
                    <li>
                        <a href="{{ action('Frontend\CollaboratorController@adminCollaboratorDownLine') }}">@lang('theme.collaborator_down_line')</a>
                    </li>
                    <li class="active">@lang('theme.collaborator') - {{ $collaborator->collaboratorInformation->code }}</li>
                </ol>
                <h1>@lang('theme.collaborator') - {{ $collaborator->collaboratorInformation->code }}</h1>
            </div>
        </div>
    </div>
</section>