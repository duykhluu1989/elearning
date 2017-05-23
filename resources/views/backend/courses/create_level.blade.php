@extends('backend.layouts.main')

@section('page_heading', 'Cấp Độ Mới')

@section('section')

    <form action="{{ action('Backend\CourseController@createLevel') }}" method="post">

        @include('backend.courses.partials.level_form')

    </form>

@stop