@extends('backend.layouts.main')

@section('page_heading', $courseItem->course->name . ' - Chỉnh Sửa Bài Học - ' . $courseItem->name)

@section('section')

    <form action="{{ action('Backend\CourseController@editCourseItem', ['id' => $courseItem->id]) }}" method="post">

        @include('backend.courses.partials.course_item_form')

    </form>

@stop