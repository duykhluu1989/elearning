<section class="banner_sub">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ action('Frontend\HomeController@home') }}"><i class="fa fa-home" aria-hidden="true"></i> @lang('theme.home')</a>
                    </li>
                    <li>
                        <a href="{{ action('Frontend\NewsController@detailCategory', ['id' => $article->category_id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($article->newsCategory, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($article->newsCategory, 'name') }}</a>
                    </li>
                    <li class="active">{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}</li>
                </ol>
                <h1>{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}</h1>
            </div>
        </div>
    </div>
</section>