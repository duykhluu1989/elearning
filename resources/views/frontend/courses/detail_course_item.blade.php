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

                                        ?>

                                        <div class="row">

                                            @if($courseItem->type == \App\Models\CourseItem::TYPE_VIDEO_DB)
                                                <video controls class="col-sm-9">
                                                    <source src="{{ action('Frontend\CourseController@getSource', ['token' => $token]) }}" type="video/{{ $type }}">
                                                </video>
                                            @else
                                                <audio controls class="col-sm-9">
                                                    <source src="{{ action('Frontend\CourseController@getSource', ['token' => $token]) }}" type="audio/{{ $type }}">
                                                </audio>
                                            @endif

                                            <div class="col-sm-3">
                                                <div class="form-group text-center">
                                                    <label class="control-label">@lang('theme.note')</label>
                                                    <?php
                                                    $studentNotes = array();
                                                    if(!empty($userCourse->student_note))
                                                        $studentNotes = json_decode($userCourse->student_note, true);
                                                    ?>
                                                    <textarea class="form-control" id="CourseItemNoteArea" rows="15">{{ isset($studentNotes[$courseItem->number]) ? $studentNotes[$courseItem->number] : '' }}</textarea>
                                                    <button type="button" id="CourseItemNoteSaveButton" class="btn btnGui">@lang('theme.save')</button>
                                                    {{ csrf_field() }}
                                                </div>
                                            </div>
                                        </div>

                                        <?php
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

@if($courseItem->type != \App\Models\CourseItem::TYPE_TEXT_DB)
    @push('scripts')
        <script type="text/javascript">
            $('#CourseItemNoteSaveButton').click(function() {
                $.ajax({
                    url: '{{ action('Frontend\CourseController@saveCourseItemNote', ['id' => $courseItem->id]) }}',
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '&note=' + $('#CourseItemNoteArea').val(),
                    success: function(result) {
                        if(result)
                        {
                            if(result == 'Success')
                                $('#CourseItemNoteArea').parent().addClass('has-success');
                            else
                                $('#CourseItemNoteArea').parent().addClass('has-error');
                        }
                    }
                });
            });
        </script>
    @endpush
@endif