<section class="banner_sub">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ action('Frontend\HomeController@home') }}"><i class="fa fa-home" aria-hidden="true"></i>@lang('theme.home')</a>
                    </li>
                    @foreach($course->categoryCourses as $categoryCourse)
                        <li><a href="{{ action('Frontend\CourseController@detailCategory', ['id' => $categoryCourse->category->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($categoryCourse->category, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($categoryCourse->category, 'name') }}</a></li>
                    @endforeach
                    <li><a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $course->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($course, 'slug')]) }}#section_noidungKH">{{ \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name') }}</a></li>
                    <li class="active">{{ \App\Libraries\Helpers\Utility::getValueByLocale($courseItem, 'name') }}</li>
                </ol>
                <h1>{{ \App\Libraries\Helpers\Utility::getValueByLocale($courseItem, 'name') }}</h1>
            </div>
        </div>
    </div>
</section>