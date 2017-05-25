@extends('backend.layouts.main')

@section('page_heading', $course->name . ' - Danh Sách Bài Học')

@section('section')

    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-4">
                    <a href="{{ action('Backend\CourseController@createCourseItem', ['id' => $course->id]) }}" class="btn btn-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="Bài Học Mới"><i class="fa fa-plus fa-fw"></i></a>
                </div>
                <div class="col-sm-4">
                    <span class="form-control no-border"><b>Tổng Số Bài Học:</b> {{ count($course->courseItems) }}</span>
                </div>
                <div class="col-sm-4">
                    <span class="form-control no-border"><b>Tổng Thời Gian Video:</b> {{ \App\Libraries\Helpers\Utility::formatTimeString($course->video_length) }}</span>
                </div>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped table-hover table-condensed">
                <tbody>
                @foreach($course->courseItems as $courseItem)
                    <tr>
                        <th>Bài Học Số {{ $courseItem->number }}</th>
                        <td>
                            <a href="{{ action('Backend\CourseController@editCourseItem', ['id' => $courseItem->id]) }}">{{ $courseItem->name }}</a>
                        </td>
                        <td>
                            @if($courseItem->type == \App\Models\CourseItem::TYPE_TEXT_DB)
                                <i class="fa fa-file-text-o fa-fw"></i>
                            @else
                                <i class="fa fa-youtube-play fa-fw"></i>
                            @endif
                            {{ \App\Models\CourseItem::getCourseItemType($courseItem->type) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop