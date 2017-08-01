<section class="banner_sub">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ action('Frontend\HomeController@home') }}"><i class="fa fa-home" aria-hidden="true"></i> @lang('theme.home')</a>
                    </li>
                    @foreach($parentCategories as $parentCategory)
                        <li><a href="{{ action('Frontend\CourseController@detailCategory', ['id' => $parentCategory->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($parentCategory, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($parentCategory, 'name') }}</a></li>
                    @endforeach
                    <li class="active">{{ \App\Libraries\Helpers\Utility::getValueByLocale($category, 'name') }}</li>
                </ol>
                <h1>{{ \App\Libraries\Helpers\Utility::getValueByLocale($category, 'name') }}</h1>
            </div>
        </div>
    </div>
</section>