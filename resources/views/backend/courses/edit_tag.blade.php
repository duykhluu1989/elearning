@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Nhãn - ' . $tag->name)

@section('section')

    <form action="{{ action('Backend\CourseController@editTag', ['id' => $tag->id]) }}" method="post">

        @include('backend.courses.partials.tag_form')

    </form>

@stop