@extends('backend.layouts.main')

@section('page_heading', $courseItem->course->name . ' - Bài Học Mới')

@section('section')

    <form action="{{ action('Backend\CourseController@createCourseItem', ['id' => $courseItem->course_id]) }}" method="post">

        @include('backend.courses.partials.course_item_form')

    </form>

@stop