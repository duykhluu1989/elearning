@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Khóa Học - ' . $course->name)

@section('section')

    <form action="{{ action('Backend\CourseController@editCourse', ['id' => $course->id]) }}" method="post">

        @include('backend.courses.partials.course_form')

    </form>

@stop