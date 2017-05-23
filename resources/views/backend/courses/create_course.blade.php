@extends('backend.layouts.main')

@section('page_heading', 'Khóa Học Mới')

@section('section')

    <form action="{{ action('Backend\CourseController@createCourse') }}" method="post">

        @include('backend.courses.partials.course_form')

    </form>

@stop