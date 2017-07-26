@extends('backend.layouts.main')

@section('page_heading', 'Chuyên Mục Mới')

@section('section')

    <form action="{{ action('Backend\NewsController@createCategory') }}" method="post">

        @include('backend.news.partials.category_form')

    </form>

@stop