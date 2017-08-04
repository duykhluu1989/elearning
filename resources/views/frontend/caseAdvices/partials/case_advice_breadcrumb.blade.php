<section class="banner_sub">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ action('Frontend\HomeController@home') }}"><i class="fa fa-home" aria-hidden="true"></i> @lang('theme.home')</a>
                    </li>
                    <li>
                        <a href="{{ action('Frontend\CaseAdviceController@adminCaseAdvice') }}">@lang('theme.case_advice')</a>
                    </li>
                    <li class="active">{{ \App\Libraries\Helpers\Utility::getValueByLocale($advice, 'name') }}</li>
                </ol>
                <h1>{{ \App\Libraries\Helpers\Utility::getValueByLocale($advice, 'name') }}</h1>
            </div>
        </div>
    </div>
</section>