@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($course, 'short_description'))

@section('og_image', $course->image)

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.courses.partials.course_item_breadcrumb')

    <main>
        <section class="bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main_content mb60">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="text-center">{{ \App\Libraries\Helpers\Utility::getValueByLocale($courseItem, 'name') }}</h3>

                                    <?php
                                    if($courseItem->type == \App\Models\CourseItem::TYPE_TEXT_DB)
                                        echo \App\Libraries\Helpers\Utility::getValueByLocale($courseItem, 'content');
                                    else
                                    {
                                        $token = \App\Libraries\Helpers\Utility::generateTemporarySourceToken(auth()->user(), \App\Libraries\Helpers\Utility::getValueByLocale($courseItem, 'content'));
                                        $file = \App\Libraries\Helpers\Utility::getValueByLocale($courseItem, 'content');
                                        $filePart = explode('.', $file);
                                        $type = $filePart[count($filePart) - 1];

                                        if($courseItem->type == \App\Models\CourseItem::TYPE_VIDEO_DB)
                                        {
                                            ?>

                                            <video controls class="col-sm-12">
                                                <source src="{{ action('Frontend\CourseController@getSource', ['token' => $token]) }}" type="video/{{ $type }}">
                                            </video>

                                            <?php
                                        }
                                        else
                                        {
                                            ?>

                                            <audio controls class="col-sm-12">
                                                <source src="{{ action('Frontend\CourseController@getSource', ['token' => $token]) }}" type="audio/{{ $type }}">
                                            </audio>

                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop