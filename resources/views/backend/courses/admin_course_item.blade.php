@extends('backend.layouts.main')

@section('page_heading', $course->name . ' - Danh Sách Bài Học')

@section('section')

    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="{{ action('Backend\CourseController@createCourseItem', ['id' => $course->id]) }}" class="btn btn-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="Bài Học Mới"><i class="fa fa-plus fa-fw"></i></a>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped table-hover table-condensed">
                <tbody>
                @foreach($course->courseItems as $courseItem)
                    <tr>
                        <td>{{ $courseItem->number }}</td>
                        <td>{{ $courseItem->name }}</td>
                        <td>{{ \App\Models\CourseItem::getCourseItemType($courseItem->type) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop