@foreach($newCourses as $newCourse)
    <article>
        <div class="row">
            <div class="col-xs-3">
                <a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $newCourse->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($newCourse, 'slug')]) }}">
                    <img src="{{ $newCourse->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($newCourse, 'name') }}" class="img-responsive w100p">
                </a>
            </div>
            <div class="col-xs-9">
                <h4>{{ \App\Libraries\Helpers\Utility::getValueByLocale($newCourse, 'name') }}</h4>
                <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($newCourse, 'short_description') }}</p>
            </div>
        </div>
    </article>
@endforeach