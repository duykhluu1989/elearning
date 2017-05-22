@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Chủ Đề - ' . $category->name)

@section('section')

    <form action="{{ action('Backend\CourseController@editCategory', ['id' => $category->id]) }}" method="post">

        @include('backend.courses.partials.category_form')

    </form>

@stop