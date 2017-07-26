@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Chuyên Mục - ' . $category->name)

@section('section')

    <form action="{{ action('Backend\NewsController@editCategory', ['id' => $category->id]) }}" method="post">

        @include('backend.news.partials.category_form')

    </form>

@stop