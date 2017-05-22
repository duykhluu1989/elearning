@extends('backend.layouts.main')

@section('page_heading', 'Chủ Đề Mới')

@section('section')

    <form action="{{ action('Backend\CourseController@createCategory') }}" method="post">

        @include('backend.courses.partials.category_form')

    </form>

@stop