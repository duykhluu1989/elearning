@extends('backend.layouts.main')

@section('page_heading', 'Nhãn Mới')

@section('section')

    <form action="{{ action('Backend\CourseController@createTag') }}" method="post">

        @include('backend.courses.partials.tag_form')

    </form>

@stop