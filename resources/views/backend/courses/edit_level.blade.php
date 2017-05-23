@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Cấp Độ - ' . $level->name)

@section('section')

    <form action="{{ action('Backend\CourseController@editLevel', ['id' => $level->id]) }}" method="post">

        @include('backend.courses.partials.level_form')

    </form>

@stop