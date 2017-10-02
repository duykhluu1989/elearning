@extends('backend.layouts.main')

@section('page_heading', 'Tin Tức Mới')

@section('section')

    <form action="{{ action('Backend\NewsController@createArticle') }}" method="post">

        @include('backend.news.partials.article_form')

    </form>

@stop