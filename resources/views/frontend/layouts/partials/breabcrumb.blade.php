<section class="banner_sub">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ action('Frontend\HomeController@home') }}"><i class="fa fa-home" aria-hidden="true"></i>@lang('theme.home')</a>
                    </li>
                    <li class="active">{{ $breabcrumbTitle }}</li>
                </ol>
                <h1>{{ $breabcrumbTitle }}</h1>
            </div>
        </div>
    </div>
</section>